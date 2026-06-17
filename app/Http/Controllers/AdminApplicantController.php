<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Applicant;
use App\Models\Admin;
use App\Models\ApplicantEducation;
use App\Models\ApplicantExperience;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell\StringCell;






class AdminApplicantController extends Controller
{
    public function create()
    {
        // Prevent creating more than one application per admin: redirect to edit if exists
        $existing = Applicant::where('admin_id', auth('admin')->id())->first();
        if ($existing) {
            return redirect()->route('admin.applicants.edit', $existing)
                ->with('info', 'You already submitted an application. You can view or edit it here.');
        }

        return view('admin.applicants.create');
    }

    public function store(Request $request)
    {
        Log::info('AdminApplicantController@store called', ['admin_guard_id' => auth('admin')->id()]);
        Log::debug('Request input (except files)', $request->except(['resume']));
        $currentYear = date('Y');
        // Prevent duplicate submission: if this admin already has an applicant, redirect to edit
        $existing = Applicant::where('admin_id', auth('admin')->id())->first();
        if ($existing) {
            return redirect()->route('admin.applicants.edit', $existing)
                ->with('info', 'You have already submitted an application. You can edit it here.');
        }
        try {
            // initial validation: allow empty fields for rows, we'll enforce "if any filled then require all" below
            // Build allowed categories list from config
            $allowedCategories = [];
            foreach (config('coe_categories', []) as $mKey => $m) {
                $allowedCategories[] = $mKey;
                if (!empty($m['subs']) && is_array($m['subs'])) {
                    foreach ($m['subs'] as $sKey => $sLabel) {
                        $allowedCategories[] = $mKey.':'.$sKey;
                    }
                }
            }

            $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email',
            'contact_number' => ['required','string','max:30','regex:/^[0-9+\\-\\s]{7,20}$/'],
                'categories' => 'required|array|min:1',
                'categories.*' => ['string', Rule::in($allowedCategories)],
            'educations' => 'required|array|min:1',
            'educations.0.qualification' => 'required|string',
            'educations.*.qualification' => 'nullable|string',
            'educations.*.institution' => 'nullable|string',
            'educations.*.year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences' => 'nullable|array',
            'experiences.*.organization' => 'nullable|string',
            'experiences.*.role' => 'nullable|string',
            'experiences.*.from_year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences.*.from_month' => 'nullable|integer|between:1,12',
            'experiences.*.to_year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences.*.to_month' => 'nullable|integer|between:1,12',
            'experiences.*.details' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'additional_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'resume.file' => 'Resume must be a valid file.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume file size must not exceed 2 MB.',
            'additional_document.file' => 'Additional Document must be a valid file.',
            'additional_document.mimes' => 'Additional Document must be a PDF, DOC, or DOCX file.',
            'additional_document.max' => 'Additional Document file size must not exceed 5 MB.',
        ]);

            // Server-side: if a main category checkbox is selected, require at least one of its subcategories
            $selected = $request->input('categories', []);
            if (!empty($selected) && is_array($selected)) {
                // collect mains selected (values without colon)
                $mains = [];
                foreach ($selected as $val) {
                    if (is_string($val) && strpos($val, ':') === false) {
                        $mains[$val] = true;
                    }
                }
                if (!empty($mains)) {
                    $missing = [];
                    foreach (array_keys($mains) as $mainKey) {
                        $found = false;
                        foreach ($selected as $val) {
                            if (strpos($val, $mainKey.':') === 0) { $found = true; break; }
                        }
                        if (!$found) $missing[] = $mainKey;
                    }
                    if (!empty($missing)) {
                        $err = [];
                        foreach ($missing as $m) {
                            $err['categories'] = 'Please select at least one subcategory for "'.($m).'".';
                        }
                        return back()->withInput()->withErrors($err);
                    }
                }
            }

            // Conditional validation: if any field in an education row is filled, require all fields
            $extraErrors = [];
            foreach ($data['educations'] as $idx => $edu) {
                $q = trim($edu['qualification'] ?? '');
                $inst = trim($edu['institution'] ?? '');
                $yr = trim((string)($edu['year'] ?? ''));
                if ($q !== '' || $inst !== '' || $yr !== '') {
                    if ($q === '') $extraErrors["educations.$idx.qualification"] = 'Qualification is required when any education field is filled.';
                    if ($inst === '') $extraErrors["educations.$idx.institution"] = 'Institution is required when any education field is filled.';
                    if ($yr === '') $extraErrors["educations.$idx.year"] = 'Year is required when any education field is filled.';
                }
            }

            // Conditional validation for experiences: if any field filled, require organization, role, from_year and to_year
            if (!empty($data['experiences']) && is_array($data['experiences'])) {
                foreach ($data['experiences'] as $idx => $exp) {
                    $org = trim($exp['organization'] ?? '');
                    $role = trim($exp['role'] ?? '');
                    $from = trim((string)($exp['from_year'] ?? ''));
                    $to = trim((string)($exp['to_year'] ?? ''));
                    $any = $org !== '' || $role !== '' || $from !== '' || $to !== '' || trim($exp['details'] ?? '') !== '';
                    if ($any) {
                        if ($org === '') $extraErrors["experiences.$idx.organization"] = 'Organization is required when any experience field is filled.';
                        if ($role === '') $extraErrors["experiences.$idx.role"] = 'Role is required when any experience field is filled.';
                        if ($from === '') $extraErrors["experiences.$idx.from_year"] = 'From Year is required when any experience field is filled.';
                        if ($to === '') $extraErrors["experiences.$idx.to_year"] = 'To Year is required when any experience field is filled.';
                    }
                }
            }

            if (!empty($extraErrors)) {
                Log::warning('Applicant conditional validation failed', ['errors' => $extraErrors]);
                return back()->withInput()->withErrors($extraErrors);
            }

            // Require at least one fully-filled education row (qualification + institution + year)
            $fullyFilled = 0;
            foreach ($data['educations'] as $edu) {
                if (trim($edu['qualification'] ?? '') !== '' && trim($edu['institution'] ?? '') !== '' && trim((string)($edu['year'] ?? '')) !== '') {
                    $fullyFilled++;
                }
            }
            if ($fullyFilled === 0) {
                $msg = ['educations' => 'At least one complete education entry (qualification, institution, year) is required.'];
                Log::warning('No complete education row provided', ['request_admin' => auth('admin')->id()]);
                return back()->withInput()->withErrors($msg);
            }
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning('Applicant validation failed', ['errors' => $ve->errors()]);
            throw $ve;
        }

        // validate experience year ranges (from <= to) and check for duplicates
        if (!empty($data['experiences']) && is_array($data['experiences'])) {
            $yearRanges = [];
            foreach ($data['experiences'] as $idx => $exp) {
                $from = $exp['from_year'] ?? null;
                $to = $exp['to_year'] ?? null;
                
                if ($from && $to && intval($from) > intval($to)) {
                    return back()->withInput()->withErrors(["experiences.$idx.to_year" => 'To Year must be greater than or equal to From Year.']);
                }
                
                // Check for duplicate year ranges
                if (!empty(trim($exp['organization'] ?? '')) && $from && $to) {
                    $range = $from . '-' . $to;
                    if (in_array($range, $yearRanges)) {
                        return back()->withInput()->withErrors(["experiences.$idx.to_year" => 'This year range is duplicate. Each experience entry must have different years.']);
                    }
                    $yearRanges[] = $range;
                }
            }
        }

        DB::beginTransaction();
        try {
            $resumePath = null;
            if ($request->file('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
                Log::info('Resume stored', ['path' => $resumePath]);
            }

            $additionalDocPath = null;
            if ($request->file('additional_document')) {
                $additionalDocPath = $request->file('additional_document')->store('additional_documents', 'public');
                Log::info('Additional document stored', ['path' => $additionalDocPath]);
            }

            $applicant = Applicant::create([
                'admin_id' => Auth::id(),
                'name' => $data['name'] ?? null,
                'address' => $data['address'],
                'email' => $data['email'],
                'contact_number' => $data['contact_number'],
                'resume_path' => $resumePath,
                'additional_document_path' => $additionalDocPath,
                'categories' => $request->input('categories') ?? null,
            ]);

            foreach ($data['educations'] as $edu) {
                // skip entirely empty education rows
                if (trim($edu['qualification'] ?? '') === '' && trim($edu['institution'] ?? '') === '' && trim((string)($edu['year'] ?? '')) === '') {
                    continue;
                }
                ApplicantEducation::create([
                    'applicant_id' => $applicant->id,
                    'qualification' => $edu['qualification'] ?? null,
                    'institution' => $edu['institution'] ?? null,
                    'year_of_passing' => $edu['year'] ?? null,
                ]);
            }

            if (!empty($data['experiences'])) {
                foreach ($data['experiences'] as $exp) {
                    if (empty(trim($exp['organization'] ?? ''))) continue;
                    ApplicantExperience::create([
                        'applicant_id' => $applicant->id,
                        'organization' => $exp['organization'] ?? null,
                        'role' => $exp['role'] ?? null,
                        'from_year' => $exp['from_year'] ?? null,
                        'from_month' => $exp['from_month'] ?? null,
                        'to_year' => $exp['to_year'] ?? null,
                        'to_month' => $exp['to_month'] ?? null,
                        'details' => $exp['details'] ?? null,
                    ]);
                }
            }

            // Calculate and save years of experience
            $applicant->updateYearsOfExperience();

            DB::commit();
            // Redirect back to the form with a success flash and applicant object so the UI can show confirmation
            return redirect()->route('admin.applicants.create')
                ->with('success', 'Applicant saved.')
                ->with('applicant', $applicant);
        } catch (\Exception $e) {
            Log::error('Failed to save applicant', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            DB::rollBack();
            if (!empty($resumePath) && Storage::disk('public')->exists($resumePath)) {
                Storage::disk('public')->delete($resumePath);
            }
            if (!empty($additionalDocPath) && Storage::disk('public')->exists($additionalDocPath)) {
                Storage::disk('public')->delete($additionalDocPath);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to save applicant: '.$e->getMessage()]);
        }
    }

    public function edit(Applicant $applicant)
    {
        return view('admin.applicants.edit', compact('applicant'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $currentYear = date('Y');
        // initial validation similar to store: allow empty row fields, we'll enforce conditional requirements below
        // allowed categories from config
        $allowedCategories = [];
        foreach (config('coe_categories', []) as $mKey => $m) {
            $allowedCategories[] = $mKey;
            if (!empty($m['subs']) && is_array($m['subs'])) {
                foreach ($m['subs'] as $sKey => $sLabel) {
                    $allowedCategories[] = $mKey.':'.$sKey;
                }
            }
        }

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email',
            'contact_number' => ['required','string','max:30','regex:/^[0-9+\\-\\s]{7,20}$/'],
            'categories' => 'required|array|min:1',
            'categories.*' => ['string', Rule::in($allowedCategories)],
            'educations' => 'required|array|min:1',
            'educations.0.qualification' => 'required|string',
            'educations.*.qualification' => 'nullable|string',
            'educations.*.institution' => 'nullable|string',
            'educations.*.year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences' => 'nullable|array',
            'experiences.*.organization' => 'nullable|string',
            'experiences.*.role' => 'nullable|string',
            'experiences.*.from_year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences.*.from_month' => 'nullable|integer|between:1,12',
            'experiences.*.to_year' => 'nullable|digits:4|integer|between:1900,'.$currentYear,
            'experiences.*.to_month' => 'nullable|integer|between:1,12',
            'experiences.*.details' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'additional_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'resume.file' => 'Resume must be a valid file.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume file size must not exceed 2 MB.',
            'additional_document.file' => 'Additional Document must be a valid file.',
            'additional_document.mimes' => 'Additional Document must be a PDF, DOC, or DOCX file.',
            'additional_document.max' => 'Additional Document file size must not exceed 5 MB.',
        ]);

        // Server-side: if a main category checkbox is selected, require at least one of its subcategories
        $selected = $request->input('categories', []);
        if (!empty($selected) && is_array($selected)) {
            $mains = [];
            foreach ($selected as $val) {
                if (is_string($val) && strpos($val, ':') === false) {
                    $mains[$val] = true;
                }
            }
            if (!empty($mains)) {
                $missing = [];
                foreach (array_keys($mains) as $mainKey) {
                    $found = false;
                    foreach ($selected as $val) {
                        if (strpos($val, $mainKey.':') === 0) { $found = true; break; }
                    }
                    if (!$found) $missing[] = $mainKey;
                }
                if (!empty($missing)) {
                    $err = [];
                    foreach ($missing as $m) {
                        $err['categories'] = 'Please select at least one subcategory for "'.($m).'".';
                    }
                    return back()->withInput()->withErrors($err);
                }
            }
        }

        // Conditional validation for update as well
        $extraErrors = [];
        foreach ($data['educations'] as $idx => $edu) {
            $q = trim($edu['qualification'] ?? '');
            $inst = trim($edu['institution'] ?? '');
            $yr = trim((string)($edu['year'] ?? ''));
            if ($q !== '' || $inst !== '' || $yr !== '') {
                if ($q === '') $extraErrors["educations.$idx.qualification"] = 'Qualification is required when any education field is filled.';
                if ($inst === '') $extraErrors["educations.$idx.institution"] = 'Institution is required when any education field is filled.';
                if ($yr === '') $extraErrors["educations.$idx.year"] = 'Year is required when any education field is filled.';
            }
        }

        if (!empty($data['experiences']) && is_array($data['experiences'])) {
            foreach ($data['experiences'] as $idx => $exp) {
                $org = trim($exp['organization'] ?? '');
                $role = trim($exp['role'] ?? '');
                $from = trim((string)($exp['from_year'] ?? ''));
                $to = trim((string)($exp['to_year'] ?? ''));
                $any = $org !== '' || $role !== '' || $from !== '' || $to !== '' || trim($exp['details'] ?? '') !== '';
                if ($any) {
                    if ($org === '') $extraErrors["experiences.$idx.organization"] = 'Organization is required when any experience field is filled.';
                    if ($role === '') $extraErrors["experiences.$idx.role"] = 'Role is required when any experience field is filled.';
                    if ($from === '') $extraErrors["experiences.$idx.from_year"] = 'From Year is required when any experience field is filled.';
                    if ($to === '') $extraErrors["experiences.$idx.to_year"] = 'To Year is required when any experience field is filled.';
                }
            }
        }

        if (!empty($extraErrors)) {
            return back()->withInput()->withErrors($extraErrors);
        }

        // Require at least one fully-filled education row on update as well
        $fullyFilled = 0;
        foreach ($data['educations'] as $edu) {
            if (trim($edu['qualification'] ?? '') !== '' && trim($edu['institution'] ?? '') !== '' && trim((string)($edu['year'] ?? '')) !== '') {
                $fullyFilled++;
            }
        }
        if ($fullyFilled === 0) {
            return back()->withInput()->withErrors(['educations' => 'At least one complete education entry (qualification, institution, year) is required.']);
        }

        // validate experience year ranges (from <= to) and check for duplicates
        if (!empty($data['experiences']) && is_array($data['experiences'])) {
            $yearRanges = [];
            foreach ($data['experiences'] as $idx => $exp) {
                $from = $exp['from_year'] ?? null;
                $to = $exp['to_year'] ?? null;
                if ($from && $to && intval($from) > intval($to)) {
                    return back()->withInput()->withErrors(["experiences.$idx.to_year" => 'To Year must be greater than or equal to From Year.']);
                }
                
                // Check for duplicate year ranges
                if (!empty(trim($exp['organization'] ?? '')) && $from && $to) {
                    $range = $from . '-' . $to;
                    if (in_array($range, $yearRanges)) {
                        return back()->withInput()->withErrors(["experiences.$idx.to_year" => 'This year range is duplicate. Each experience entry must have different years.']);
                    }
                    $yearRanges[] = $range;
                }
            }
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('resume')) {
                $newPath = $request->file('resume')->store('resumes', 'public');
                if ($applicant->resume_path && Storage::disk('public')->exists($applicant->resume_path)) {
                    Storage::disk('public')->delete($applicant->resume_path);
                }
                $applicant->resume_path = $newPath;
            }

            if ($request->hasFile('additional_document')) {
                $newAdditionalDocPath = $request->file('additional_document')->store('additional_documents', 'public');
                if ($applicant->additional_document_path && Storage::disk('public')->exists($applicant->additional_document_path)) {
                    Storage::disk('public')->delete($applicant->additional_document_path);
                }
                $applicant->additional_document_path = $newAdditionalDocPath;
            }

            $applicant->update([
                'name' => $data['name'] ?? null,
                'address' => $data['address'],
                'email' => $data['email'],
                'contact_number' => $data['contact_number'],
                'categories' => $request->input('categories') ?? $applicant->categories,
            ]);

            // Replace educations & experiences: simplest approach - delete and re-insert
            $applicant->educations()->delete();
            foreach ($data['educations'] as $edu) {
                if (trim($edu['qualification'] ?? '') === '' && trim($edu['institution'] ?? '') === '' && trim((string)($edu['year'] ?? '')) === '') {
                    continue;
                }
                ApplicantEducation::create([
                    'applicant_id' => $applicant->id,
                    'qualification' => $edu['qualification'] ?? null,
                    'institution' => $edu['institution'] ?? null,
                    'year_of_passing' => $edu['year'] ?? null,
                ]);
            }

            $applicant->experiences()->delete();
            if (!empty($data['experiences'])) {
                foreach ($data['experiences'] as $exp) {
                    if (empty(trim($exp['organization'] ?? ''))) continue;
                    ApplicantExperience::create([
                        'applicant_id' => $applicant->id,
                        'organization' => $exp['organization'] ?? null,
                        'role' => $exp['role'] ?? null,
                        'from_year' => $exp['from_year'] ?? null,
                        'from_month' => $exp['from_month'] ?? null,
                        'to_year' => $exp['to_year'] ?? null,
                        'to_month' => $exp['to_month'] ?? null,
                        'details' => $exp['details'] ?? null,
                    ]);
                }
            }
            // Calculate and save years of experience
            $applicant->updateYearsOfExperience();
            DB::commit();
            return redirect()->route('admin.applicants.show', $applicant)->with('success', 'Applicant updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update applicant: '.$e->getMessage()]);
        }
    }

    public function show(Applicant $applicant)
    {
        return view('admin.applicants.show', compact('applicant'));
    }

    public function index(Request $request)
    {
        $currentAdmin = auth('admin')->user();
        
        // Start with base query filtered by team (superadmin sees all or team, regular admin sees only themselves)
        if ($currentAdmin->is_super) {
            // Get applicants from all admins (superadmins see everything)
            // If filtering by specific admin, use that; otherwise show all
            if ($request->filled('admin_id')) {
                $query = Applicant::where('admin_id', $request->input('admin_id'))
                    ->withCount(['educations','experiences']);
            } else {
                $query = Applicant::withCount(['educations','experiences']);
            }
        } else {
            // Regular admin only sees their own applicant
            $query = Applicant::where('admin_id', $currentAdmin->id)
                ->withCount(['educations','experiences']);
        }
        
        // Filter by main category
        if ($request->filled('main_category')) {
            $mainCategory = $request->input('main_category');
            $query->where(function($q) use ($mainCategory) {
                $q->whereJsonContains('categories', $mainCategory)
                  ->orWhereJsonContains('categories', $mainCategory . ':');
            });
        }
        
        // Filter by subcategory
        if ($request->filled('sub_category')) {
            $subCategory = $request->input('sub_category');
            $query->whereJsonContains('categories', $subCategory);
        }
        
        // Filter by experience years
        if ($request->filled('experience_years')) {
            $expYears = $request->input('experience_years');
            
            if ($expYears === '0-7') {
                $query->whereBetween('years_of_experience', [0, 7]);
            } elseif ($expYears === '7-10') {
                $query->whereBetween('years_of_experience', [7, 10]);
            } elseif ($expYears === '10-15') {
                $query->whereBetween('years_of_experience', [10, 15]);
            } elseif ($expYears === '15-20') {
                $query->whereBetween('years_of_experience', [15, 20]);
            } elseif ($expYears === '20-25') {
                $query->whereBetween('years_of_experience', [20, 25]);
            } elseif ($expYears === '25+') {
                $query->where('years_of_experience', '>=', 25);
            }
        }
        
        $applicants = $query->orderBy('name', 'asc')->paginate(20);
        
        // Get categories config for filter dropdowns
        $categoriesConfig = config('coe_categories', []);
        
        // Get list of all admins for superadmin filter
        $teamAdmins = [];
        if ($currentAdmin->is_super) {
            $teamAdmins = Admin::where('is_super', false)
                ->orderBy('name')
                ->get();
        }
        
        return view('admin.applicants.index', compact('applicants', 'categoriesConfig', 'teamAdmins', 'currentAdmin'));
    }

    /**
     * Parse categories array and format for display
     * Categories stored as: ["area1", "area1:sub1", "area2", "area2:sub1", ...]
     * Returns simple format: Category: Main\nSub-Category: Sub1\nCategory: Main2\nSub-Category: Sub2
     */
    private function formatCategoriesForExport($categories)
    {
        if (empty($categories)) {
            return '';
        }
        
        $config = config('coe_categories', []);
        $organized = [];
        
        foreach ((array)$categories as $cat) {
            if (strpos($cat, ':') !== false) {
                // It's a subcategory like "area1:sub1"
                [$mainKey, $subKey] = explode(':', $cat);
                if (isset($config[$mainKey]) && isset($config[$mainKey]['subs'][$subKey])) {
                    $mainLabel = $config[$mainKey]['label'];
                    $subLabel = $config[$mainKey]['subs'][$subKey];
                    
                    // Initialize main category group if not exists
                    if (!isset($organized[$mainKey])) {
                        $organized[$mainKey] = [
                            'label' => $mainLabel,
                            'subs' => []
                        ];
                    }
                    
                    // Add subcategory
                    if (!in_array($subLabel, $organized[$mainKey]['subs'])) {
                        $organized[$mainKey]['subs'][] = $subLabel;
                    }
                }
            } else {
                // It's a main category
                if (isset($config[$cat])) {
                    $mainLabel = $config[$cat]['label'];
                    
                    // Initialize main category if not exists
                    if (!isset($organized[$cat])) {
                        $organized[$cat] = [
                            'label' => $mainLabel,
                            'subs' => []
                        ];
                    }
                }
            }
        }
        
        // Format output: Category: xyz, Sub-Category: xyz1
        $lines = [];
        foreach ($organized as $item) {
            $lines[] = "Category: " . $item['label'];
            if (!empty($item['subs'])) {
                foreach ($item['subs'] as $sub) {
                    $lines[] = "Sub-Category: " . $sub;
                }
            }
        }
        
        return implode("\n", $lines);
    }

    public function export(Request $request)
    {
        // Apply the same filters as the index method
        $query = Applicant::with(['educations', 'experiences'])->withCount(['educations','experiences']);
        
        // Filter by main category
        if ($request->filled('main_category')) {
            $mainCategory = $request->input('main_category');
            $query->where(function($q) use ($mainCategory) {
                $q->whereJsonContains('categories', $mainCategory)
                  ->orWhereJsonContains('categories', $mainCategory . ':');
            });
        }
        
        // Filter by subcategory
        if ($request->filled('sub_category')) {
            $subCategory = $request->input('sub_category');
            $query->whereJsonContains('categories', $subCategory);
        }
        
        // Filter by experience years
        if ($request->filled('experience_years')) {
            $expYears = $request->input('experience_years');
            
            if ($expYears === '0-7') {
                $query->whereBetween('years_of_experience', [0, 7]);
            } elseif ($expYears === '7-10') {
                $query->whereBetween('years_of_experience', [7, 10]);
            } elseif ($expYears === '10-15') {
                $query->whereBetween('years_of_experience', [10, 15]);
            } elseif ($expYears === '15-20') {
                $query->whereBetween('years_of_experience', [15, 20]);
            } elseif ($expYears === '20-25') {
                $query->whereBetween('years_of_experience', [20, 25]);
            } elseif ($expYears === '25+') {
                $query->where('years_of_experience', '>=', 25);
            }
        }
        
        // Get filtered applicants
        $applicants = $query->latest()->get();
        $filename = 'applicants_'.date('Y-m-d_His').'.xlsx';
        
        $tempFile = sys_get_temp_dir() . '/' . $filename;
        $writer = new Writer();
        $writer->openToFile($tempFile);
        
        // Write header row
        $headerCells = [
            new StringCell('ID', null),
            new StringCell('Name', null),
            new StringCell('Email', null),
            new StringCell('Contact', null),
            new StringCell('Address', null),
            new StringCell('Domain Knowledge', null),
            new StringCell('Years of Experience', null),
            new StringCell('Areas & Subcategories', null),
            new StringCell('Education - Qualification', null),
            new StringCell('Education - Institution', null),
            new StringCell('Education - Year', null),
            new StringCell('Experience - Organization', null),
            new StringCell('Experience - Role', null),
            new StringCell('Experience - From Year', null),
            new StringCell('Experience - To Year', null),
            new StringCell('Experience - Details', null),
            new StringCell('Created At', null),
        ];
        $writer->addRow(new Row($headerCells));
        
        // Write data rows
        foreach ($applicants as $app) {
            // Get all educations and experiences
            $educations = $app->educations ?? [];
            $experiences = $app->experiences ?? [];
            
            // Format categories for this applicant
            $categoryText = $this->formatCategoriesForExport($app->categories);
            
            // If no education/experience, still write applicant info once
            if (empty($educations) && empty($experiences)) {
                $cells = [
                    new StringCell($app->id, null),
                    new StringCell($app->name ?? '', null),
                    new StringCell($app->email, null),
                    new StringCell($app->contact_number, null),
                    new StringCell($app->address ?? '', null),
                    new StringCell($app->domain_knowledge ?? '', null),
                    new StringCell($app->years_of_experience ?? '0', null),
                    new StringCell($categoryText, null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell('', null),
                    new StringCell($app->created_at->format('Y-m-d H:i:s'), null),
                ];
                $writer->addRow(new Row($cells));
            } else {
                // Write rows for each education/experience entry
                $maxRows = max(count($educations), count($experiences), 1);
                
                for ($i = 0; $i < $maxRows; $i++) {
                    $education = $educations[$i] ?? null;
                    $experience = $experiences[$i] ?? null;
                    
                    $cells = [
                        new StringCell($i === 0 ? $app->id : '', null),
                        new StringCell($i === 0 ? ($app->name ?? '') : '', null),
                        new StringCell($i === 0 ? $app->email : '', null),
                        new StringCell($i === 0 ? $app->contact_number : '', null),
                        new StringCell($i === 0 ? ($app->address ?? '') : '', null),
                        new StringCell($i === 0 ? ($app->domain_knowledge ?? '') : '', null),
                        new StringCell($i === 0 ? ($app->years_of_experience ?? '0') : '', null),
                        new StringCell($i === 0 ? $categoryText : '', null),
                        new StringCell($education ? ($education->qualification ?? '') : '', null),
                        new StringCell($education ? ($education->institution ?? '') : '', null),
                        new StringCell($education ? ($education->year_of_passing ?? '') : '', null),
                        new StringCell($experience ? ($experience->organization ?? '') : '', null),
                        new StringCell($experience ? ($experience->role ?? '') : '', null),
                        new StringCell($experience ? ($experience->from_year ?? '') : '', null),
                        new StringCell($experience ? ($experience->to_year ?? '') : '', null),
                        new StringCell($experience ? ($experience->details ?? '') : '', null),
                        new StringCell($i === 0 ? $app->created_at->format('Y-m-d H:i:s') : '', null),
                    ];
                    $writer->addRow(new Row($cells));
                }
            }
        }
        
        $writer->close();
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function destroy(Applicant $applicant)
    {
        // Check if current admin is super admin
        $currentAdmin = Auth::guard('admin')->user();
        if (!$currentAdmin || !$currentAdmin->is_super) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized action.');
        }

        try {
            // Delete related records first
            ApplicantEducation::where('applicant_id', $applicant->id)->delete();
            ApplicantExperience::where('applicant_id', $applicant->id)->delete();

            // Delete associated files if they exist
            if ($applicant->resume_path) {
                Storage::disk('public')->delete($applicant->resume_path);
            }
            if ($applicant->additional_document_path) {
                Storage::disk('public')->delete($applicant->additional_document_path);
            }

            // Delete the associated admin record if it exists
            if ($applicant->admin_id) {
                Admin::where('id', $applicant->admin_id)->delete();
            }

            // Delete the applicant record
            $applicant->delete();

            return redirect()->route('admin.applicants.index')->with('success', 'Applicant and associated admin account deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting applicant', ['applicant_id' => $applicant->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.applicants.index')->with('error', 'Failed to delete applicant. Please try again.');
        }
    }
}

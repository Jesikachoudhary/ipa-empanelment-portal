@extends('layouts.admin_inner')

@section('content')
@php
    $categoryConfig = config('applicant_types.types', []);
    $currentCategory = $applicant->applicant_type;
    $categoryLabel = '';
    $categoryColor = '';
    
    if ($currentCategory && isset($categoryConfig[$currentCategory])) {
        $categoryLabel = $categoryConfig[$currentCategory]['label'];
        $categoryColor = $categoryConfig[$currentCategory]['color'];
    }
@endphp
<div style=" {{ $categoryColor }}cc 100%) !important; color: white !important; padding: 30px 15px !important; margin: -20px -20px 20px -20px !important; position: relative !important; z-index: 10 !important; display: block !important;">
    <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="flex: 1;">
            <h2 style="color: white !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; font-size: 28px !important; display: block !important;">Centre of Excellence- Empanelment of Emerging Talent and Aspiring Professionals</h2>
            @if($categoryLabel)
                <p style="color: rgba(255, 255, 255, 0.95) !important; margin: 8px 0 0 0 !important; font-size: 16px !important; display: block !important;"><strong>Category:</strong> {{ $categoryLabel }}</p>
            @endif
            <p style="color: rgba(255, 255, 255, 0.9) !important; margin: 4px 0 0 0 !important; font-size: 14px !important; display: block !important;"><strong>FY:</strong> {{ $applicant->application_fy ?? \App\Models\Applicant::getCurrentFinancialYear() }}</p>
        </div>
        <div>
            <a href="{{ route('admin.applicants.select-category') }}" class="btn btn-light btn-sm" style="margin-left: 20px; font-weight: 600;">
                <i class="zmdi zmdi-dashboard"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">

    @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form id="applicant-form" action="{{ route('admin.applicants.update', $applicant) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $applicant->name) }}" >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Address <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $applicant->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Email <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $applicant->email) }}" >
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <div class="form-group"><input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number', $applicant->contact_number) }}">
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Date of Birth <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $applicant->date_of_birth?->format('Y-m-d')) }}" title="Date format: MM/DD/YYYY">
                            <small style="color: #6c757d; margin-top: 5px; display: block;">The candidate should not be more than 40 years of age as on 01 May 2026.</small>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            Resume <span class="text-danger">*</span>
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 2 MB"></i>
                        </label>
                        <div class="form-group">
                            <input type="file" name="resume" class="form-control file-input @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx" data-field="resume" data-max-size="2097152" data-allowed-types="pdf,doc,docx">
                            <small class="file-helper-text d-none" style="color: #6c757d; margin-top: 5px; display: block;">Max size: 2 MB | Formats: PDF, DOC, DOCX</small>
                        </div>
                        <div class="file-notification resume-notification d-none" style="margin-top: 10px;"></div>
                        @if($applicant->resume_path)
                            <div><a href="{{ asset('storage/'.$applicant->resume_path) }}" target="_blank">Download current resume</a></div>
                        @endif
                        @error('resume')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                        
                        <label>
                            Additional Document (Optional)
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 5 MB"></i>
                        </label>
                        <div class="form-group">
                            <input type="file" name="additional_document" class="form-control file-input @error('additional_document') is-invalid @enderror" accept=".pdf,.doc,.docx" data-field="additional_document" data-max-size="5242880" data-allowed-types="pdf,doc,docx">
                            <small class="file-helper-text d-none" style="color: #6c757d; margin-top: 5px; display: block;">Max size: 5 MB | Formats: PDF, DOC, DOCX</small>
                        </div>
                        <div class="file-notification additional-document-notification d-none" style="margin-top: 10px;"></div>
                        @if($applicant->additional_document_path)
                            <div><a href="{{ asset('storage/'.$applicant->additional_document_path) }}" target="_blank">Download current additional document</a></div>
                        @endif
                        @error('additional_document')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr>
                <h5>Educational Qualifications <span class="text-danger">*</span></h5>
                <div id="educations" class="education-container">
                    @php
                        // Pre-load all educations with their documents
                        $allEducationsWithDocs = $applicant->educations()->with('documents')->get()->keyBy('id');
                        
                        $oldEducations = old('educations');
                        if (is_null($oldEducations)) {
                            $oldEducations = $applicant->educations->load('documents')->map(function($e){
                                return ['id' => $e->id, 'qualification'=>$e->qualification,'institution'=>$e->institution,'year'=>$e->year_of_passing];
                            })->toArray();
                        } else {
                            // Enhance old() data with IDs from database for existing rows
                            $dbEducations = $applicant->educations->load('documents')->keyBy('id')->toArray();
                            $allDbEducations = $applicant->educations->load('documents')->toArray();
                            $usedIds = [];
                            
                            foreach ($oldEducations as $i => &$row) {
                                // If row already has an ID, keep it (from hidden input)
                                if (!empty($row['id'])) {
                                    $usedIds[] = $row['id'];
                                    continue;
                                }
                                
                                // Try to match by qualification + institution combination first
                                if (!empty($row['qualification'])) {
                                    $matched = false;
                                    foreach ($allDbEducations as $dbRow) {
                                        if (!in_array($dbRow['id'], $usedIds) && 
                                            $row['qualification'] === $dbRow['qualification'] &&
                                            (empty($row['institution']) || $row['institution'] === $dbRow['institution'])) {
                                            $row['id'] = $dbRow['id'];
                                            $usedIds[] = $dbRow['id'];
                                            $matched = true;
                                            break;
                                        }
                                    }
                                    if ($matched) continue;
                                    
                                    // Try matching by qualification only if institution didn't match
                                    foreach ($allDbEducations as $dbRow) {
                                        if (!in_array($dbRow['id'], $usedIds) && 
                                            $row['qualification'] === $dbRow['qualification']) {
                                            $row['id'] = $dbRow['id'];
                                            $usedIds[] = $dbRow['id'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (empty($oldEducations)) { $oldEducations = [['qualification'=>'','institution'=>'','year'=>'']]; }
                    @endphp
                    @foreach($oldEducations as $i => $eduRow)
                        <div class="education-row row mb-2" data-index="{{ $i }}" data-edu-id="{{ $eduRow['id'] ?? '' }}">
                            <div class="col-md-12 mb-2" style="padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                                <strong style="font-size: 16px; color: #333;">Education #{{ $i + 1 }}</strong>
                                @php
                                    $eduId = $eduRow['id'] ?? null;
                                    $hasDocs = false;
                                    if ($eduId && isset($allEducationsWithDocs[$eduId])) {
                                        $edu = $allEducationsWithDocs[$eduId];
                                        $hasDocs = $edu->documents && $edu->documents->count() > 0;
                                    }
                                @endphp
                                @if($hasDocs)
                                    <span style="display: inline-block; margin-left: 10px; background-color: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;"><i class="zmdi zmdi-check-circle" style="margin-right: 4px;"></i>Document Uploaded</span>
                                @endif
                            </div>
                            <input type="hidden" name="educations[{{ $i }}][id]" value="{{ $eduRow['id'] ?? '' }}">
                            <div class="col-md-5">
                                <input type="text" name="educations[{{ $i }}][qualification]" placeholder="Qualification" class="form-control @if($errors->has('educations.'.$i.'.qualification')) is-invalid @endif" value="{{ $eduRow['qualification'] ?? '' }}">
                                @if($errors->has('educations.'.$i.'.qualification'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.qualification') }}</div>@endif
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="educations[{{ $i }}][institution]" placeholder="Institution" class="form-control @if($errors->has('educations.'.$i.'.institution')) is-invalid @endif" value="{{ $eduRow['institution'] ?? '' }}">
                                @if($errors->has('educations.'.$i.'.institution'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.institution') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="educations[{{ $i }}][year]" class="form-control ms @if($errors->has('educations.'.$i.'.year')) is-invalid @endif">
                                    <option value="">Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($eduRow['year']) && $eduRow['year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('educations.'.$i.'.year'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.year') }}</div>@endif
                            </div>
                            <div class="col-md-2 remove-btn-col" style="display: @if($i == 0) none @else inline-block @endif;">
                                <button type="button" class="btn btn-sm btn-danger remove-education" title="Remove this education"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                            </div>
                        </div>
                        <div class="education-doc-row row mb-3 ms-2" data-index="{{ $i }}" style="border-left: 3px solid #e0e0e0; padding-left: 15px;">
                            <div class="col-md-10">
                                <label style="font-size: 13px; color: #666;">
                                    Document <span class="doc-required-badge" style="color: #dc3545; font-weight: bold;">*</span>
                                    <i class="zmdi zmdi-info-outline" style="font-size: 12px; color: #0066cc; cursor: help;" title="PDF, DOC, DOCX files. Max 2MB - Required if qualification is filled"></i>
                                </label>
                                @php
                                    $eduId = $eduRow['id'] ?? null;
                                    $docs = collect([]);
                                    if ($eduId && isset($allEducationsWithDocs[$eduId])) {
                                        $edu = $allEducationsWithDocs[$eduId];
                                        $docs = $edu->documents ?? collect([]);
                                    }
                                @endphp
                                @if($docs && $docs->count() > 0)
                                    <div style="margin-bottom: 10px; padding: 8px; background-color: #f8f9fa; border-radius: 4px; border-left: 3px solid #28a745; display: block;">
                                        <div style="font-size: 12px; font-weight: 600; color: #28a745; margin-bottom: 6px;"><i class="zmdi zmdi-check-circle" style="margin-right: 4px;"></i>Existing Document(s)</div>
                                        @foreach($docs as $doc)
                                            <div style="margin: 4px 0;">
                                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" style="font-size: 11px;"><i class="zmdi zmdi-download" style="margin-right: 4px;"></i>View Document</a>
                                                <span style="font-size: 11px; color: #666; margin-left: 8px;">{{ basename($doc->file_path) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div style="margin-bottom: 8px;">
                                    <input type="file" name="educations[{{ $i }}][document]" class="form-control form-control-sm edu-doc-upload @if($errors->has('educations.'.$i.'.document')) is-invalid @endif" accept=".pdf,.doc,.docx" data-index="{{ $i }}" data-max-size="2097152" data-allowed-types="pdf,doc,docx">
                                    <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 3px;">Max: 2MB | Formats: PDF, DOC, DOCX</small>
                                    <div class="edu-file-selected-{{ $i }}" style="margin-top: 6px; display: none; padding: 6px; background-color: #e8f5e9; border-radius: 3px;">
                                        <i class="zmdi zmdi-file" style="color: #4caf50; margin-right: 4px;"></i><span class="edu-file-name-{{ $i }}" style="font-size: 12px; color: #2e7d32; font-weight: 500;"></span>
                                    </div>
                                </div>
                                @if($errors->has('educations.'.$i.'.document'))<div class="invalid-feedback" style="display: block;">{{ $errors->first('educations.'.$i.'.document') }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-education" class="btn btn-sm btn-primary">Add Education</button>

                <hr>
                <h5>Experience (Organisation-wise)</h5>
                <div id="experiences" class="experience-container">
                    @php
                        // Pre-load all experiences with their documents
                        $allExperiencesWithDocs = $applicant->experiences()->with('documents')->get()->keyBy('id');
                        
                        $oldExps = old('experiences');
                        if (is_null($oldExps)) {
                            $oldExps = $applicant->experiences->load('documents')->map(function($e){
                                return ['id' => $e->id, 'organization'=>$e->organization,'role'=>$e->role,'from_year'=>$e->from_year,'from_month'=>$e->from_month,'to_year'=>$e->to_year,'to_month'=>$e->to_month,'details'=>$e->details];
                            })->toArray();
                        } else {
                            // Enhance old() data with IDs from database for existing rows
                            $dbExperiences = $applicant->experiences->load('documents')->keyBy('id')->toArray();
                            $allDbExperiences = $applicant->experiences->load('documents')->toArray();
                            $usedIds = [];
                            
                            foreach ($oldExps as $j => &$row) {
                                // If row already has an ID, keep it (from hidden input)
                                if (!empty($row['id'])) {
                                    $usedIds[] = $row['id'];
                                    continue;
                                }
                                
                                // Try to match by organization + role combination first
                                if (!empty($row['organization'])) {
                                    $matched = false;
                                    foreach ($allDbExperiences as $dbRow) {
                                        if (!in_array($dbRow['id'], $usedIds) && 
                                            $row['organization'] === $dbRow['organization'] &&
                                            (empty($row['role']) || $row['role'] === $dbRow['role'])) {
                                            $row['id'] = $dbRow['id'];
                                            $usedIds[] = $dbRow['id'];
                                            $matched = true;
                                            break;
                                        }
                                    }
                                    if ($matched) continue;
                                    
                                    // Try matching by organization only if role didn't match
                                    foreach ($allDbExperiences as $dbRow) {
                                        if (!in_array($dbRow['id'], $usedIds) && 
                                            $row['organization'] === $dbRow['organization']) {
                                            $row['id'] = $dbRow['id'];
                                            $usedIds[] = $dbRow['id'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (empty($oldExps)) { $oldExps = [['organization'=>'','role'=>'','from_year'=>'','from_month'=>'','to_year'=>'','to_month'=>'','details'=>'']]; }
                    @endphp
                    @foreach($oldExps as $j => $exp)
                        <div class="experience-row row mb-2" data-index="{{ $j }}" data-exp-id="{{ $exp['id'] ?? '' }}">
                            <div class="col-md-12 mb-2" style="padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                                <strong style="font-size: 16px; color: #333;">Experience #{{ $j + 1 }}</strong>
                                @php
                                    $expId = $exp['id'] ?? null;
                                    $hasDocs = false;
                                    if ($expId && isset($allExperiencesWithDocs[$expId])) {
                                        $expModel = $allExperiencesWithDocs[$expId];
                                        $hasDocs = $expModel->documents && $expModel->documents->count() > 0;
                                    }
                                @endphp
                                @if($hasDocs)
                                    <span style="display: inline-block; margin-left: 10px; background-color: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;"><i class="zmdi zmdi-check-circle" style="margin-right: 4px;"></i>Document Uploaded</span>
                                @endif
                            </div>
                            <input type="hidden" name="experiences[{{ $j }}][id]" value="{{ $exp['id'] ?? '' }}">
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $j }}][organization]" placeholder="Organization" class="form-control @if($errors->has('experiences.'.$j.'.organization')) is-invalid @endif" value="{{ $exp['organization'] ?? '' }}">
                                @if($errors->has('experiences.'.$j.'.organization'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.organization') }}</div>@endif
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $j }}][role]" placeholder="Role" class="form-control @if($errors->has('experiences.'.$j.'.role')) is-invalid @endif" value="{{ $exp['role'] ?? '' }}">
                                @if($errors->has('experiences.'.$j.'.role'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.role') }}</div>@endif
                            </div>
                            <div class="col-md-2 remove-btn-col" style="display: @if($j == 0) none @else inline-block @endif;">
                                <button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                            </div>
                        </div>
                        <div class="row mb-2 ms-2">
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][from_year]" class="form-control ms from-year @if($errors->has('experiences.'.$j.'.from_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">From Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($exp['from_year']) && $exp['from_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$j.'.from_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.from_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][from_month]" class="form-control ms from-month @if($errors->has('experiences.'.$j.'.from_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($exp['from_month']) && $exp['from_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$j.'.from_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.from_month') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][to_year]" class="form-control ms to-year @if($errors->has('experiences.'.$j.'.to_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">To Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($exp['to_year']) && $exp['to_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$j.'.to_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.to_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][to_month]" class="form-control ms to-month @if($errors->has('experiences.'.$j.'.to_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($exp['to_month']) && $exp['to_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$j.'.to_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.to_month') }}</div>@endif
                            </div>
                            <div class="col-md-4">
                                <textarea name="experiences[{{ $j }}][details]" placeholder="Details" class="form-control @if($errors->has('experiences.'.$j.'.details')) is-invalid @endif">{{ $exp['details'] ?? '' }}</textarea>
                                @if($errors->has('experiences.'.$j.'.details'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.details') }}</div>@endif
                            </div>
                        </div>
                        <div class="experience-doc-row row mb-3 ms-2" data-index="{{ $j }}" style="border-left: 3px solid #e0e0e0; padding-left: 15px;">
                            <div class="col-md-10">
                                <label style="font-size: 13px; color: #666;">
                                    Document <span class="doc-required-badge" style="color: #dc3545; font-weight: bold;">*</span>
                                    <i class="zmdi zmdi-info-outline" style="font-size: 12px; color: #0066cc; cursor: help;" title="PDF, DOC, DOCX files. Max 2MB - Required if organization is filled"></i>
                                </label>
                                @php
                                    $expId = $exp['id'] ?? null;
                                    $docs = collect([]);
                                    if ($expId && isset($allExperiencesWithDocs[$expId])) {
                                        $expModel = $allExperiencesWithDocs[$expId];
                                        $docs = $expModel->documents ?? collect([]);
                                    }
                                @endphp
                                @if($docs && $docs->count() > 0)
                                    <div style="margin-bottom: 10px; padding: 8px; background-color: #f8f9fa; border-radius: 4px; border-left: 3px solid #28a745; display: block;">
                                        <div style="font-size: 12px; font-weight: 600; color: #28a745; margin-bottom: 6px;"><i class="zmdi zmdi-check-circle" style="margin-right: 4px;"></i>Existing Document(s)</div>
                                        @foreach($docs as $doc)
                                            <div style="margin: 4px 0;">
                                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" style="font-size: 11px;"><i class="zmdi zmdi-download" style="margin-right: 4px;"></i>View Document</a>
                                                <span style="font-size: 11px; color: #666; margin-left: 8px;">{{ basename($doc->file_path) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div style="margin-bottom: 8px;">
                                    <input type="file" name="experiences[{{ $j }}][document]" class="form-control form-control-sm exp-doc-upload @if($errors->has('experiences.'.$j.'.document')) is-invalid @endif" accept=".pdf,.doc,.docx" data-index="{{ $j }}" data-max-size="2097152" data-allowed-types="pdf,doc,docx">
                                    <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 3px;">Max: 2MB | Formats: PDF, DOC, DOCX</small>
                                    <div class="exp-file-selected-{{ $j }}" style="margin-top: 6px; display: none; padding: 6px; background-color: #e3f2fd; border-radius: 3px;">
                                        <i class="zmdi zmdi-file" style="color: #1976d2; margin-right: 4px;"></i><span class="exp-file-name-{{ $j }}" style="font-size: 12px; color: #0d47a1; font-weight: 500;"></span>
                                    </div>
                                </div>
                                @if($errors->has('experiences.'.$j.'.document'))<div class="invalid-feedback" style="display: block;">{{ $errors->first('experiences.'.$j.'.document') }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-experience" class="btn btn-sm btn-primary">Add Experience</button>

                <hr>
                <h5>Areas & Subcategories  <span class="text-danger">*</span></h5>
                @php
                    $categories = config('coe_categories');
                    $selected = old('categories', $applicant->categories ?? []);
                @endphp
                @error('categories')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row">
                    @foreach($categories as $mainKey => $main)
                        @php $mainChecked = in_array($mainKey, $selected); @endphp
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input main-cat" type="checkbox" id="cat_{{ $mainKey }}" value="{{ $mainKey }}" name="categories[]" {{ in_array($mainKey, $selected) ? 'checked' : '' }} />
                                <label class="form-check-label font-weight-bold" for="cat_{{ $mainKey }}">{{ $main['label'] }}</label>
                            </div>
                            <div class="subcats mt-2" id="sub_{{ $mainKey }}" style="display: {{ $mainChecked ? 'block' : 'none' }}; padding-left:1rem;">
                                @foreach($main['subs'] as $subKey => $subLabel)
                                    @php $val = $mainKey.':'.$subKey; @endphp
                                    <div class="form-check">
                                        <input class="form-check-input sub-cat" type="checkbox" id="sub_{{ $mainKey }}_{{ $subKey }}" name="categories[]" value="{{ $val }}" {{ in_array($val, $selected) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="sub_{{ $mainKey }}_{{ $subKey }}">{{ $subLabel }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <script>
                    document.addEventListener('click', function(e){
                        if (e.target && e.target.classList.contains('main-cat')) {
                            const id = e.target.id.replace('cat_','');
                            const sub = document.getElementById('sub_'+id);
                            if (!sub) return;
                            if (e.target.checked) sub.style.display = 'block'; else sub.style.display = 'none';
                        }
                    });
                </script>

                <hr>
                <div class="text-right">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    (function(){
        let eduIndex = document.querySelectorAll('#educations .education-row').length;
        document.getElementById('add-education').addEventListener('click', function(){
            const container = document.getElementById('educations');
            
            // Generate year options
            let yearOptions = '<option value="">Year</option>';
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1960; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
            }
            
            const eduRow = document.createElement('div');
            eduRow.className = 'education-row row mb-2';
            eduRow.setAttribute('data-index', eduIndex);
            eduRow.innerHTML = `
                <div class="col-md-12 mb-2" style="padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;"><strong style="font-size: 16px; color: #333;">Education #${eduIndex + 1}</strong></div>
                <div class="col-md-5"><input type="text" name="educations[${eduIndex}][qualification]" placeholder="Qualification" class="form-control"></div>
                <div class="col-md-3"><input type="text" name="educations[${eduIndex}][institution]" placeholder="Institution" class="form-control"></div>
                <div class="col-md-2"><select name="educations[${eduIndex}][year]" class="form-control ms">${yearOptions}</select></div>
                <div class="col-md-2 remove-btn-col" style="display: inline-block;"><button type="button" class="btn btn-sm btn-danger remove-education" title="Remove this education"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button></div>
            `;
            container.appendChild(eduRow);

            const docRow = document.createElement('div');
            docRow.className = 'education-doc-row row mb-3 ms-2';
            docRow.setAttribute('data-index', eduIndex);
            docRow.style.cssText = 'border-left: 3px solid #e0e0e0; padding-left: 15px;';
            docRow.innerHTML = `
                <div class="col-md-10">
                    <label style="font-size: 13px; color: #666;">
                        Document <span class="doc-required-badge" style="color: #dc3545; font-weight: bold;">*</span>
                        <i class="zmdi zmdi-info-outline" style="font-size: 12px; color: #0066cc; cursor: help;" title="PDF, DOC, DOCX files. Max 2MB - Required if qualification is filled"></i>
                    </label>
                    <div style="margin-bottom: 8px;">
                        <input type="file" name="educations[${eduIndex}][document]" class="form-control form-control-sm edu-doc-upload" accept=".pdf,.doc,.docx" data-index="${eduIndex}" data-max-size="2097152" data-allowed-types="pdf,doc,docx">
                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 3px;">Max: 2MB | Formats: PDF, DOC, DOCX</small>
                        <div class="edu-file-selected-${eduIndex}" style="margin-top: 6px; display: none; padding: 6px; background-color: #e8f5e9; border-radius: 3px;">
                            <i class="zmdi zmdi-file" style="color: #4caf50; margin-right: 4px;"></i><span class="edu-file-name-${eduIndex}" style="font-size: 12px; color: #2e7d32; font-weight: 500;"></span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(docRow);
            eduIndex++;
        });

        let expIndex = document.querySelectorAll('#experiences .experience-row').length;
        document.getElementById('add-experience').addEventListener('click', function(){
            const container = document.getElementById('experiences');
            
            // Generate year options
            let yearOptions = '<option value="">Year</option>';
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1960; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
            }
            
            // Generate month options
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            let monthOptions = '<option value="">Month</option>';
            months.forEach((month, i) => {
                monthOptions += `<option value="${i + 1}">${month}</option>`;
            });
            
            // First row: organization, role, remove button
            const div1 = document.createElement('div');
            div1.className = 'experience-row row mb-2';
            div1.setAttribute('data-index', expIndex);
            div1.innerHTML = `
                <div class="col-md-12 mb-2" style="padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;"><strong style="font-size: 16px; color: #333;">Experience #${expIndex + 1}</strong></div>
                <div class="col-md-5"><input type="text" name="experiences[${expIndex}][organization]" placeholder="Organization" class="form-control"></div>
                <div class="col-md-5"><input type="text" name="experiences[${expIndex}][role]" placeholder="Role" class="form-control"></div>
                <div class="col-md-2 remove-btn-col" style="display: inline-block;"><button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button></div>
            `;
            container.appendChild(div1);
            
            // Second row: years, months, and details
            const div2 = document.createElement('div');
            div2.className = 'experience-year-month-row row mb-2 ms-2';
            div2.setAttribute('data-index', expIndex);
            div2.innerHTML = `
                <div class="col-md-2"><select name="experiences[${expIndex}][from_year]" class="form-control ms from-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;"><option value="">From Year</option>${yearOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][from_month]" class="form-control ms from-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">${monthOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][to_year]" class="form-control ms to-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;"><option value="">To Year</option>${yearOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][to_month]" class="form-control ms to-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">${monthOptions}</select></div>
                <div class="col-md-4"><textarea name="experiences[${expIndex}][details]" placeholder="Details" class="form-control"></textarea></div>
            `;
            container.appendChild(div2);

            // Third row: document upload
            const div3 = document.createElement('div');
            div3.className = 'experience-doc-row row mb-3 ms-2';
            div3.setAttribute('data-index', expIndex);
            div3.style.cssText = 'border-left: 3px solid #e0e0e0; padding-left: 15px;';
            div3.innerHTML = `
                <div class="col-md-10">
                    <label style="font-size: 13px; color: #666;">
                        Document <span class="doc-required-badge" style="color: #dc3545; font-weight: bold;">*</span>
                        <i class="zmdi zmdi-info-outline" style="font-size: 12px; color: #0066cc; cursor: help;" title="PDF, DOC, DOCX files. Max 2MB - Required if organization is filled"></i>
                    </label>
                    <div style="margin-bottom: 8px;">
                        <input type="file" name="experiences[${expIndex}][document]" class="form-control form-control-sm exp-doc-upload" accept=".pdf,.doc,.docx" data-index="${expIndex}" data-max-size="2097152" data-allowed-types="pdf,doc,docx">
                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 3px;">Max: 2MB | Formats: PDF, DOC, DOCX</small>
                        <div class="exp-file-selected-${expIndex}" style="margin-top: 6px; display: none; padding: 6px; background-color: #e3f2fd; border-radius: 3px;">
                            <i class="zmdi zmdi-file" style="color: #1976d2; margin-right: 4px;"></i><span class="exp-file-name-${expIndex}" style="font-size: 12px; color: #0d47a1; font-weight: 500;"></span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(div3);
            expIndex++;
        });

        // Defensive: remove any plugin-inserted search inputs that appear before our year <select class="ms">
        window.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('select.ms').forEach(function(sel){
                try {
                    const prev = sel.previousElementSibling;
                    if (!prev) return;
                    const isAnonTextInput = prev.tagName === 'INPUT' && prev.type === 'text' && !prev.name;
                    const isBsSearch = prev.classList && (prev.classList.contains('bs-searchbox') || prev.className.includes('select2'));
                    if (isAnonTextInput || isBsSearch) {
                        prev.remove();
                    }
                } catch(e) { /* noop */ }
            });
        });

        // Remove education row
        document.addEventListener('click', function(e){
            if (e.target.closest('.remove-education')) {
                e.preventDefault();
                const row = e.target.closest('.education-row');
                if (row) {
                    // Capture next siblings BEFORE removing anything
                    let nextRow = row.nextElementSibling;
                    let docRow = null;
                    if (nextRow && nextRow.classList.contains('education-doc-row')) {
                        docRow = nextRow;
                    }
                    
                    // Now remove all rows
                    row.remove();
                    if (docRow) {
                        docRow.remove();
                    }
                }
            }
        });

        // Remove experience row
        document.addEventListener('click', function(e){
            if (e.target.closest('.remove-experience')) {
                e.preventDefault();
                const row = e.target.closest('.experience-row');
                if (row) {
                    // Capture all three related rows BEFORE removing anything
                    let yearMonthRow = row.nextElementSibling;
                    let docRow = null;
                    
                    // Check if next sibling is the year/month row
                    if (yearMonthRow && yearMonthRow.classList.contains('row') && yearMonthRow.classList.contains('mb-2') && yearMonthRow.classList.contains('ms-2')) {
                        // Check if row after that is the document row
                        docRow = yearMonthRow.nextElementSibling;
                        if (docRow && !docRow.classList.contains('experience-doc-row')) {
                            docRow = null;
                        }
                    } else {
                        yearMonthRow = null;
                    }
                    
                    // Now remove all three rows
                    row.remove();
                    if (yearMonthRow) {
                        yearMonthRow.remove();
                    }
                    if (docRow) {
                        docRow.remove();
                    }
                }
            }
        });

        // Calculate experience duration function
        function calculateExperienceDuration(e) {
            const row = e.target.closest('.row.mb-2');
            if (!row) return;
            
            const fromYearSelect = row.querySelector('select.from-year');
            const fromMonthSelect = row.querySelector('select.from-month');
            const toYearSelect = row.querySelector('select.to-year');
            const toMonthSelect = row.querySelector('select.to-month');
            const calcField = row.querySelector('input.exp-calc');
            
            if (!fromYearSelect || !toYearSelect || !calcField) return;
            
            const fromYear = parseInt(fromYearSelect.value) || 0;
            const fromMonth = parseInt(fromMonthSelect.value) || 1;
            const toYear = parseInt(toYearSelect.value) || 0;
            const toMonth = parseInt(toMonthSelect.value) || 12;
            
            if (!fromYear || !toYear) {
                calcField.value = '';
                return;
            }
            
            // Calculate total months difference
            const totalMonths = ((toYear - fromYear) * 12) + (toMonth - fromMonth);
            
            if (totalMonths < 0) {
                calcField.value = 'Invalid range';
                return;
            }
            
            // Convert to years and months
            const years = Math.floor(totalMonths / 12);
            const months = totalMonths % 12;
            
            if (years === 0) {
                calcField.value = months + 'M';
            } else if (months === 0) {
                calcField.value = years + 'Y';
            } else {
                calcField.value = years + 'Y ' + months + 'M';
            }
        }

        // Attach change listeners to all experience fields
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('exp-change')) {
                calculateExperienceDuration(e);
            }
        });

        // Calculate on page load
        window.addEventListener('load', function() {
            document.querySelectorAll('.row.mb-2 select.exp-change').forEach(select => {
                const event = new Event('change', { bubbles: true });
                select.dispatchEvent(event);
            });
        });

        // client-side validation
        const phoneField = document.querySelector('input[name="contact_number"]');
        const phoneRegex = /^[0-9]{10}$/;

        function showFieldError(field, msg) {
            if (!field) return;
            field.classList.add('is-invalid');
            const existing = field.parentNode.querySelector('.invalid-feedback');
            if (existing) existing.remove();
            const fb = document.createElement('div');
            fb.className = 'invalid-feedback';
            fb.textContent = msg;
            if (field.nextSibling) field.parentNode.insertBefore(fb, field.nextSibling); else field.parentNode.appendChild(fb);
        }

        function clearFieldError(field) {
            if (!field) return;
            field.classList.remove('is-invalid');
            const fb = field.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.remove();
        }

        if (phoneField) {
            phoneField.addEventListener('input', function(){
                if (phoneRegex.test(this.value)) clearFieldError(this); else showFieldError(this,'⚠️ Mobile number must be exactly 10 digits');
            });
        }

        // Auto-remove errors when field is being edited/filled
        document.querySelectorAll('#applicant-form input, #applicant-form textarea, #applicant-form select').forEach(function(field) {
            const events = field.type === 'file' ? ['change'] : field.tagName === 'SELECT' ? ['change'] : ['input', 'change'];
            events.forEach(function(eventType) {
                field.addEventListener(eventType, function() {
                    if (field.classList.contains('is-invalid')) {
                        clearFieldError(field);
                    }
                });
            });
        });

        // Auto-remove errors from checkboxes when clicked
        document.querySelectorAll('#applicant-form input[type="checkbox"]').forEach(function(field) {
            field.addEventListener('change', function() {
                if (field.classList.contains('is-invalid')) {
                    clearFieldError(field);
                }
            });
        });

        function validateYears() {
            const errors = [];
            document.querySelectorAll('#experiences .experience-row').forEach(function(row, idx){
                const from = row.querySelector('input[name^="experiences"][name$="[from_year]"]');
                const to = row.querySelector('input[name^="experiences"][name$="[to_year]"]');
                if (from && to && from.value && to.value) {
                    if (parseInt(from.value) > parseInt(to.value)) {
                        showFieldError(to, 'To Year must be >= From Year');
                        errors.push(to);
                    } else {
                        clearFieldError(to);
                    }
                }
            });
            return errors.length === 0;
        }

        document.getElementById('applicant-form').addEventListener('submit', function(e){
            console.log('Form submit event triggered');
            let valid = true;
            
            // Check if at least ONE main category is checked
            const mainCheckboxes = document.querySelectorAll('input[type="checkbox"].main-cat');
            let anyMainChecked = false;
            mainCheckboxes.forEach(function(cb){ if (cb.checked) anyMainChecked = true; });
            console.log('anyMainChecked:', anyMainChecked, 'Total mains:', mainCheckboxes.length);
            
            if (!anyMainChecked) {
                e.preventDefault();
                valid = false;
                const firstMain = document.querySelector('input[type="checkbox"].main-cat');
                if (firstMain) {
                    showFieldError(firstMain, '⚠️ Please select at least one main category');
                    firstMain.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
                console.log('BLOCKED: No main category selected');
                return false;
            }
            
            // Check if subcategories are selected for each checked main
            mainCheckboxes.forEach(function(mainCb){
                if (!mainCb.checked) return;
                const mainKey = mainCb.value;
                const subDiv = document.getElementById('sub_' + mainKey);
                let hasSubCat = false;
                if (subDiv) {
                    const checked = subDiv.querySelectorAll('input[type="checkbox"].sub-cat:checked');
                    hasSubCat = checked.length > 0;
                }
                if (!hasSubCat) {
                    e.preventDefault();
                    valid = false;
                    showFieldError(mainCb, '⚠️ Please select at least one subcategory for: ' + mainCb.parentElement.textContent.trim());
                    mainCb.scrollIntoView({behavior: 'smooth', block: 'center'});
                    console.log('BLOCKED: No subcategory for', mainKey);
                }
            });
            
            // phone
            if (phoneField && !phoneRegex.test(phoneField.value)) {
                e.preventDefault();
                showFieldError(phoneField, '⚠️ Mobile number must be exactly 10 digits');
                valid = false;
            }
            // years
            if (!validateYears()) {
                e.preventDefault();
                valid = false;
            }

            if (!valid) {
                console.log('Form validation FAILED - preventing submission');
            }

            // Ensure first education qualification is present
            const firstQual = document.querySelector('#educations .education-row [name$="[qualification]"]');
            if (firstQual && firstQual.value.trim() === '') {
                e.preventDefault();
                showFieldError(firstQual, '⚠️ Qualification is required');
                valid = false;
            }

            // educations completeness
            document.querySelectorAll('#educations .education-row').forEach(function(row, idx){
                const q = row.querySelector('[name^="educations"][name$="[qualification]"]');
                const inst = row.querySelector('[name^="educations"][name$="[institution]"]');
                const yr = row.querySelector('[name^="educations"][name$="[year]"]');
                const any = (q && q.value.trim() !== '') || (inst && inst.value.trim() !== '') || (yr && yr.value.trim() !== '');
                if (any) {
                    if (q && q.value.trim() === '') { e.preventDefault(); showFieldError(q, '⚠️ Qualification is required'); valid = false; }
                    if (inst && inst.value.trim() === '') { e.preventDefault(); showFieldError(inst, '⚠️ Institution is required'); valid = false; }
                    if (yr && yr.value.trim() === '') { e.preventDefault(); showFieldError(yr, '⚠️ Year is required'); valid = false; }
                    
                    // Check if document is uploaded when any field is filled
                    const docRow = document.querySelector(`.education-doc-row[data-index="${idx}"]`);
                    const existingDocLink = docRow ? docRow.querySelector('a[href*="storage"]') : null;
                    const docInput = docRow ? docRow.querySelector('input[name^="educations"][name$="[document]"]') : null;
                    
                    // Only require document if no existing document AND no new file selected
                    if (docInput && !docInput.files.length && !existingDocLink) {
                        e.preventDefault();
                        showFieldError(docInput, '⚠️ Document is required when education details are filled');
                        valid = false;
                    }
                }
            });

            // experiences completeness
            document.querySelectorAll('#experiences .experience-row').forEach(function(row, idx){
                const org = row.querySelector('[name^="experiences"][name$="[organization]"]');
                const role = row.querySelector('[name^="experiences"][name$="[role]"]');
                const from = row.querySelector('[name^="experiences"][name$="[from_year]"]');
                const to = row.querySelector('[name^="experiences"][name$="[to_year]"]');
                const details = row.querySelector('input[name^="experiences"][name$="[details]"]') || row.querySelector('textarea[name^="experiences"][name$="[details]"]');
                const any = (org && org.value.trim() !== '') || (role && role.value.trim() !== '') || (from && from.value.trim() !== '') || (to && to.value.trim() !== '') || (details && details.value.trim() !== '');
                if (any) {
                    if (org && org.value.trim() === '') { e.preventDefault(); showFieldError(org, '⚠️ Organization is required'); valid = false; }
                    if (role && role.value.trim() === '') { e.preventDefault(); showFieldError(role, '⚠️ Role is required'); valid = false; }
                    if (from && from.value.trim() === '') { e.preventDefault(); showFieldError(from, '⚠️ From Year is required'); valid = false; }
                    if (to && to.value.trim() === '') { e.preventDefault(); showFieldError(to, '⚠️ To Year is required'); valid = false; }
                    
                    // Check if document is uploaded when any field is filled
                    const docRow = document.querySelector(`.experience-doc-row[data-index="${idx}"]`);
                    const existingDocLink = docRow ? docRow.querySelector('a[href*="storage"]') : null;
                    const docInput = docRow ? docRow.querySelector('input[name^="experiences"][name$="[document]"]') : null;
                    
                    // Only require document if no existing document AND no new file selected
                    if (docInput && !docInput.files.length && !existingDocLink) {
                        e.preventDefault();
                        showFieldError(docInput, '⚠️ Document is required when experience details are filled');
                        valid = false;
                    }
                }
            });

            if (!valid) {
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
                    firstInvalid.focus();
                }
            }
        });

        window.addEventListener('load', function(){
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) { firstInvalid.scrollIntoView({behavior:'smooth', block:'center'}); firstInvalid.focus(); }
        });

        // Show selected file names for education documents
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('edu-doc-upload')) {
                const index = e.target.dataset.index;
                const selectedDiv = document.querySelector(`.edu-file-selected-${index}`);
                const fileNameSpan = document.querySelector(`.edu-file-name-${index}`);
                const docRow = e.target.closest('.education-doc-row');
                const rowDiv = docRow ? docRow.previousElementSibling : null;
                
                if (e.target.files.length > 0) {
                    const fileName = e.target.files[0].name;
                    fileNameSpan.textContent = fileName;
                    selectedDiv.style.display = 'block';
                    
                    // Add badge to row title if not already present
                    if (rowDiv && !rowDiv.querySelector('[style*="background-color: #28a745"]')) {
                        const badge = document.createElement('span');
                        badge.style.cssText = 'display: inline-block; margin-left: 10px; background-color: #ffc107; color: #000; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;';
                        badge.innerHTML = '<i class="zmdi zmdi-file" style="margin-right: 4px;"></i>File Selected';
                        rowDiv.querySelector('strong').parentNode.appendChild(badge);
                    }
                } else {
                    selectedDiv.style.display = 'none';
                    
                    // Remove "File Selected" badge if no file and no existing document
                    if (rowDiv) {
                        const existingDocDiv = docRow.querySelector('[style*="background-color: #f8f9fa"]');
                        const hasExisting = existingDocDiv && existingDocDiv.innerHTML.trim();
                        if (!hasExisting) {
                            const fileBadge = rowDiv.querySelector('span:has(i.zmdi-file)');
                            if (fileBadge) fileBadge.remove();
                        }
                    }
                }
            }
        });

        // Show selected file names for experience documents
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('exp-doc-upload')) {
                const index = e.target.dataset.index;
                const selectedDiv = document.querySelector(`.exp-file-selected-${index}`);
                const fileNameSpan = document.querySelector(`.exp-file-name-${index}`);
                const docRow = e.target.closest('.experience-doc-row');
                const rowDiv = docRow ? docRow.parentElement.querySelector('.experience-row[data-index="' + index + '"]') : null;
                
                if (e.target.files.length > 0) {
                    const fileName = e.target.files[0].name;
                    fileNameSpan.textContent = fileName;
                    selectedDiv.style.display = 'block';
                    
                    // Add badge to row title if not already present
                    if (rowDiv && !rowDiv.querySelector('span:has(i.zmdi-file)')) {
                        const badge = document.createElement('span');
                        badge.style.cssText = 'display: inline-block; margin-left: 10px; background-color: #ffc107; color: #000; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;';
                        badge.innerHTML = '<i class="zmdi zmdi-file" style="margin-right: 4px;"></i>File Selected';
                        rowDiv.querySelector('strong').parentNode.appendChild(badge);
                    }
                } else {
                    selectedDiv.style.display = 'none';
                    
                    // Remove "File Selected" badge if no file and no existing document
                    if (rowDiv) {
                        const existingDocDiv = docRow.querySelector('[style*="background-color: #f8f9fa"]');
                        const hasExisting = existingDocDiv && existingDocDiv.innerHTML.trim();
                        if (!hasExisting) {
                            const fileBadge = rowDiv.querySelector('span:has(i.zmdi-file)');
                            if (fileBadge) fileBadge.remove();
                        }
                    }
                }
            }
        });

        // Initialize document display status on page load and DOM ready
        function initializeDocumentDisplay() {
            // Check each education row for existing documents
            document.querySelectorAll('.education-doc-row').forEach(docRow => {
                const index = docRow.dataset.index;
                const existingDocDiv = docRow.querySelector('[style*="background-color: #f8f9fa"]');
                const selectedFileDiv = docRow.querySelector(`.edu-file-selected-${index}`);
                const fileInput = docRow.querySelector(`.edu-doc-upload`);
                
                // Show existing document div if it has content
                if (existingDocDiv) {
                    const hasContent = existingDocDiv.querySelector('a') !== null || existingDocDiv.innerHTML.trim().length > 50;
                    if (hasContent) {
                        existingDocDiv.style.display = 'block';
                    }
                }
                
                // Show indicator if file selected
                if (fileInput && fileInput.files.length > 0 && selectedFileDiv) {
                    const fileName = fileInput.files[0].name;
                    selectedFileDiv.querySelector(`.edu-file-name-${index}`).textContent = fileName;
                    selectedFileDiv.style.display = 'block';
                }
            });

            // Check each experience row for existing documents
            document.querySelectorAll('.experience-doc-row').forEach(docRow => {
                const index = docRow.dataset.index;
                const existingDocDiv = docRow.querySelector('[style*="background-color: #f8f9fa"]');
                const selectedFileDiv = docRow.querySelector(`.exp-file-selected-${index}`);
                const fileInput = docRow.querySelector(`.exp-doc-upload`);
                
                // Show existing document div if it has content
                if (existingDocDiv) {
                    const hasContent = existingDocDiv.querySelector('a') !== null || existingDocDiv.innerHTML.trim().length > 50;
                    if (hasContent) {
                        existingDocDiv.style.display = 'block';
                    }
                }
                
                // Show indicator if file selected
                if (fileInput && fileInput.files.length > 0 && selectedFileDiv) {
                    const fileName = fileInput.files[0].name;
                    selectedFileDiv.querySelector(`.exp-file-name-${index}`).textContent = fileName;
                    selectedFileDiv.style.display = 'block';
                }
            });
        }

        // Initialize on DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDocumentDisplay);
        } else {
            initializeDocumentDisplay();
        }

        // Also initialize on window load
        window.addEventListener('load', initializeDocumentDisplay);

        // File Upload Validation
        const fileInputs = document.querySelectorAll('input.file-input[type="file"]');
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function getFileExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }

        function showFileNotification(fieldName, message, type = 'error') {
            const notificationDiv = document.querySelector(`.${fieldName}-notification`);
            if (!notificationDiv) return;

            // Remove existing notification classes
            notificationDiv.classList.remove('alert', 'alert-danger', 'alert-warning', 'alert-success');
            
            // Add appropriate alert class based on type
            let alertClass = 'alert-danger';
            if (type === 'warning') alertClass = 'alert-warning';
            if (type === 'success') alertClass = 'alert-success';
            
            notificationDiv.classList.add('alert', alertClass);
            notificationDiv.innerHTML = `<i class="zmdi ${type === 'error' ? 'zmdi-alert-circle' : type === 'warning' ? 'zmdi-alert-polygon' : 'zmdi-check-circle'}" style="margin-right: 8px;"></i>${message}`;
            notificationDiv.classList.remove('d-none');
        }

        function hideFileNotification(fieldName) {
            const notificationDiv = document.querySelector(`.${fieldName}-notification`);
            if (notificationDiv) {
                notificationDiv.classList.add('d-none');
            }
        }

        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const fieldName = this.dataset.field;
                const maxSize = parseInt(this.dataset.maxSize);
                const allowedTypes = this.dataset.allowedTypes.split(',');
                const file = this.files[0];

                if (!file) {
                    hideFileNotification(fieldName);
                    this.classList.remove('is-invalid');
                    return;
                }

                // Validate file type
                const fileExtension = getFileExtension(file.name);
                if (!allowedTypes.includes(fileExtension)) {
                    const errorMsg = `❌ Invalid file format. Allowed formats: ${allowedTypes.map(t => t.toUpperCase()).join(', ')}`;
                    showFileNotification(fieldName, errorMsg, 'error');
                    this.classList.add('is-invalid');
                    this.value = ''; // Clear the input
                    return;
                }

                // Validate file size
                if (file.size > maxSize) {
                    const maxSizeMB = Math.floor(maxSize / 1024 / 1024);
                    const fileSizeMB = formatFileSize(file.size);
                    const errorMsg = `❌ File size (${fileSizeMB}) exceeds the maximum limit of ${maxSizeMB} MB`;
                    showFileNotification(fieldName, errorMsg, 'error');
                    this.classList.add('is-invalid');
                    this.value = ''; // Clear the input
                    return;
                }

                // Success notification
                const successMsg = `✓ File "${file.name}" (${formatFileSize(file.size)}) selected successfully`;
                showFileNotification(fieldName, successMsg, 'success');
                this.classList.remove('is-invalid');
            });

            // Add file input focus/blur handlers for better UX
            input.addEventListener('focus', function() {
                document.querySelector(`.${this.dataset.field}-notification`).classList.add('d-none');
            });
        });

        // Add validation on form submission
        document.getElementById('applicant-form').addEventListener('submit', function(e) {
            let isValid = true;
            
            fileInputs.forEach(input => {
                if (input.classList.contains('is-invalid')) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                const firstInvalidFile = document.querySelector('input.file-input.is-invalid');
                if (firstInvalidFile) {
                    firstInvalidFile.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
                return false;
            }
        }, {capture: true}); // Use capture phase to run before other listeners
    })();
</script>
@endpush

@endsection

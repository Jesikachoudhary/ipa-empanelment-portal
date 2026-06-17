@extends('layouts.admin_inner')

@section('header')
    <h2>Applicants</h2>
@endsection



@section('content')
<style>
    /* Aggressively hide bootstrap-select - remove from document flow */
    .bs-container {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        overflow: hidden !important;
    }
    
    button.selectpicker {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
    }
    
    .dropdown-menu.show {
        display: none !important;
    }
    
    /* Ensure native selects are visible and clickable with dropdown arrow */
    #filter-form select {
        display: inline-block !important;
        visibility: visible !important;
        height: auto !important;
        width: auto !important;
        appearance: auto !important;
        -webkit-appearance: auto !important;
        cursor: pointer !important;
    }
    
    /* Hide dropdown arrow from bootstrap-select button */
    .bs-container button.selectpicker::after {
        display: none !important;
    }
</style>

<div class="container-fluid">
   
    <div class="card">
        <div style="padding: 10px 15px; border-bottom: 1px solid #e3e3e3; display: flex; justify-content: space-between; align-items: center;">
            <form id="filter-form" method="GET" action="{{ route('admin.applicants.index') }}" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; flex: 1;">
                <select name="main_category" style="min-width: 180px; padding: 6px 10px; font-size: 12px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Categories</option>
                    @foreach($categoriesConfig as $mainKey => $main)
                        <option value="{{ $mainKey }}" @if(request('main_category') === $mainKey) selected @endif>
                            {{ $main['label'] }}
                        </option>
                    @endforeach
                </select>
                
                <select id="sub_category_select" name="sub_category" style="min-width: 220px; padding: 6px 10px; font-size: 12px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Sub-Categories</option>
                    @php
                        $allSubcategories = [];
                        foreach($categoriesConfig as $mainKey => $main) {
                            foreach($main['subs'] as $subKey => $subLabel) {
                                $allSubcategories[$mainKey . ':' . $subKey] = $subLabel . ' (' . $main['label'] . ')';
                            }
                        }
                    @endphp
                    @foreach($allSubcategories as $value => $label)
                        <option value="{{ $value }}" data-main="{{ explode(':', $value)[0] }}" @if(request('sub_category') === $value) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                
                <select name="experience_years" style="min-width: 150px; padding: 6px 10px; font-size: 12px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Experience Years</option>
                    <option value="0-7" @if(request('experience_years') === '0-7') selected @endif>0-7 years</option>
                    <option value="7-10" @if(request('experience_years') === '7-10') selected @endif>7-10 years</option>
                    <option value="10-15" @if(request('experience_years') === '10-15') selected @endif>10-15 years</option>
                    <option value="15-20" @if(request('experience_years') === '15-20') selected @endif>15-20 years</option>
                    <option value="20-25" @if(request('experience_years') === '20-25') selected @endif>20-25 years</option>
                    <option value="25+" @if(request('experience_years') === '25+') selected @endif>>25 years</option>
                </select>
                
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 6px 12px; font-size: 12px;">Filter</button>
                <a href="{{ route('admin.applicants.index') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">Clear</a>
                @if($applicants->count() > 0)
                    <a href="{{ route('admin.applicants.export') . '?' . request()->getQueryString() }}" class="btn btn-success btn-sm" style="padding: 6px 12px; font-size: 12px;">
                        <i class="fa fa-download"></i> Export
                    </a>
                @endif
            </form>
            
            <input type="text" id="liveSearch" placeholder="Search list..." style="margin-left: 10px; padding: 6px 10px; font-size: 12px; border: 1px solid #ddd; border-radius: 4px; width: 180px;">
        </div>

        <div class="body table-responsive">
            @if($applicants->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if($currentAdmin->is_super)
                                <th>Submitted By</th>
                            @endif
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Categories</th>
                            <th>Exp Years</th>
                            <!--<th>Edu</th>-->
                            <!--<th>Experiences Details</th>-->
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicants as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                @if($currentAdmin->is_super)
                                    <td><strong>{{ $a->admin->name ?? '—' }}</strong></td>
                                @endif
                                <td>{{ $a->name ?? '—' }}</td>
                                <td>{{ $a->email }}</td>
                                <td>{{ $a->contact_number }}</td>
                                <td>
                                    @php
                                        $mainCategories = [];
                                        if ($a->categories && is_array($a->categories)) {
                                            foreach ($a->categories as $cat) {
                                                // Extract main category (before the colon)
                                                $mainCat = explode(':', $cat)[0];
                                                if (!in_array($mainCat, $mainCategories)) {
                                                    $mainCategories[] = $mainCat;
                                                }
                                            }
                                        }
                                        // Convert to uppercase
                                        $mainCategories = array_map('strtoupper', $mainCategories);
                                    @endphp
                                    <small>{{ implode(', ', $mainCategories) ?: '—' }}</small>
                                </td>
                                <td>
                                    @php
                                        $totalMonths = 0;
                                        foreach ($a->experiences as $exp) {
                                            if ($exp->from_year && $exp->to_year) {
                                                $fromMonth = (int)($exp->from_month ?? 12);
                                                $toMonth = (int)($exp->to_month ?? 12);
                                                $fromYear = (int)$exp->from_year;
                                                $toYear = (int)$exp->to_year;
                                                $monthsDiff = (($toYear - $fromYear) * 12) + ($toMonth - $fromMonth) + 1;
                                                if ($monthsDiff > 0) {
                                                    $totalMonths += $monthsDiff;
                                                }
                                            }
                                        }
                                        $yearsDisplay = intdiv($totalMonths, 12);
                                        $monthsDisplay = $totalMonths % 12;
                                        if ($monthsDisplay === 0) {
                                            $expText = $yearsDisplay . ' year' . ($yearsDisplay !== 1 ? 's' : '');
                                        } else {
                                            $expText = $yearsDisplay . ' year' . ($yearsDisplay !== 1 ? 's' : '') . ' ' . $monthsDisplay . ' month' . ($monthsDisplay !== 1 ? 's' : '');
                                        }
                                    @endphp
                                    <strong>{{ $expText ?: '—' }}</strong>
                                </td>
                                <!--<td>{{ $a->educations_count }}</td>-->
                               <!-- <td>
                                    @if($a->experiences_count > 0)
                                        @php
                                            $experiences = $a->experiences()->orderBy('from_year', 'desc')->get();
                                        @endphp
                                        <small>
                                            @php
                                                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                            @endphp
                                            @foreach($experiences as $exp)
                                                @php
                                                    $fromMonth = (int)($exp->from_month ?? 12);
                                                    $toMonth = (int)($exp->to_month ?? 12);
                                                    $fromYear = (int)$exp->from_year;
                                                    $toYear = (int)$exp->to_year;
                                                    
                                                    // Calculate duration in months (inclusive)
                                                    $monthsDiff = (($toYear - $fromYear) * 12) + ($toMonth - $fromMonth) + 1;
                                                    
                                                    // Convert to years and months
                                                    $yearsCalc = intdiv($monthsDiff, 12);
                                                    $monthsCalc = $monthsDiff % 12;
                                                    
                                                    // Format duration string
                                                    if ($monthsCalc === 0) {
                                                        $durationText = $yearsCalc . ' year' . ($yearsCalc !== 1 ? 's' : '');
                                                    } else {
                                                        $durationText = $yearsCalc . ' year' . ($yearsCalc !== 1 ? 's' : '') . ' ' . $monthsCalc . ' month' . ($monthsCalc !== 1 ? 's' : '');
                                                    }
                                                    
                                                    $fromMonthStr = isset($months[$fromMonth]) ? $months[$fromMonth] : 'Jan';
                                                    $toMonthStr = isset($months[$toMonth]) ? $months[$toMonth] : 'Dec';
                                                @endphp
                                                <span title="{{ $exp->organization }} ({{ $exp->from_year }}-{{ $fromMonthStr }} to {{ $exp->to_year }}-{{ $toMonthStr }})">{{ $durationText }}</span><br>
                                            @endforeach
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>-->
                                <td>{{ $a->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.applicants.show', $a) }}" class="btn btn-sm btn-primary">View</a>
                                    @if($currentAdmin->is_super)
                                        <form method="POST" action="{{ route('admin.applicants.destroy', $a) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this applicant?\n\n⚠️ WARNING: This action cannot be undone!\n\nYou will lose:\n• Personal information (name, email, contact)\n• Resume and Additional Documents\n• Educational Qualifications\n• Work Experience\n• Category selections\n\nClick OK to permanently delete or Cancel to go back.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $applicants->appends(request()->query())->links() }}
            @else
                <div style="padding: 40px; text-align: center; color: #999;">
                    <h5>No Data Found</h5>
                    <p>No applicants match your current filters.</p>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainCategorySelect = document.querySelector('select[name="main_category"]');
        const subCategorySelect = document.getElementById('sub_category_select');
        
        // Function to filter sub-categories based on main category
        function filterSubcategories() {
            const selectedMain = mainCategorySelect.value;
            const subOptions = subCategorySelect.querySelectorAll('option');
            
            subOptions.forEach(option => {
                if (option.value === '') {
                    // Always show "All Sub-Categories"
                    option.style.display = '';
                } else {
                    const dataMain = option.getAttribute('data-main');
                    if (selectedMain === '' || dataMain === selectedMain) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
            
            // Reset sub-category selection when main category changes
            if (selectedMain === '') {
                subCategorySelect.value = '';
            }
        }
        
        // Listen for main category changes
        if (mainCategorySelect && subCategorySelect) {
            mainCategorySelect.addEventListener('change', filterSubcategories);
            // Initial filter on page load
            filterSubcategories();
        }
        
        const filterForm = document.getElementById('filter-form');
        
        if (filterForm) {
            // Remove any existing bootstrap-select elements
            filterForm.querySelectorAll('.bs-container, button.selectpicker').forEach(el => el.remove());
            
            // Remove selectpicker class from all selects
            filterForm.querySelectorAll('select.selectpicker').forEach(select => {
                select.classList.remove('selectpicker');
            });
            
            // Watch for new bootstrap-select elements being added and remove them immediately
            const observer = new MutationObserver(function(mutations) {
                filterForm.querySelectorAll('.bs-container, button.selectpicker').forEach(el => {
                    if (filterForm.contains(el) || el.parentElement === filterForm) {
                        el.remove();
                    }
                });
            });
            
            observer.observe(filterForm, {
                childList: true,
                subtree: true,
                attributes: false,
                characterData: false
            });
        }
    });
    
    // Live search functionality
    const liveSearch = document.getElementById('liveSearch');
    if (liveSearch) {
        liveSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.table tbody tr');
            
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
</script>

@endsection

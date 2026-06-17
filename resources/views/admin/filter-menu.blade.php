@php
    $currentFy = \App\Models\Applicant::getCurrentFinancialYear();
    // Get distinct FYs this admin has applications for (year-wise)
    $adminId = auth('admin')->id();
    $fyOptions = \App\Models\Applicant::where('admin_id', $adminId)
        ->distinct()
        ->pluck('application_fy')
        ->filter()
        ->sortDesc()
        ->values()
        ->toArray();
    if (empty($fyOptions)) {
        $fyOptions = [$currentFy];
    }
    $selectedFy = request('fy');
@endphp

<style>
    .filter-menu-container {
        position: relative;
        display: inline-block;
    }

    .filter-btn {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background: #667eea;
        color: white;
    }

    .filter-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        min-width: 200px;
        margin-top: 8px;
    }

    .filter-dropdown.show {
        display: block;
    }

    .filter-dropdown-header {
        padding: 12px 16px;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        color: #333;
    }

    .filter-dropdown-content {
        max-height: 300px;
        overflow-y: auto;
    }

    .filter-item {
        padding: 10px 16px;
        cursor: pointer;
        transition: background 0.2s;
        color: #555;
    }

    .filter-item:hover {
        background: #f5f5f5;
    }

    .filter-item.active {
        background: #e3f2fd;
        color: #667eea;
        font-weight: 600;
    }

    .filter-item a {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .reset-filter-btn {
        padding: 10px 16px;
        border-top: 1px solid #eee;
        background: #f9f9f9;
        cursor: pointer;
        text-align: center;
        color: #d9534f;
        font-weight: 600;
        border-radius: 0 0 8px 8px;
        transition: background 0.2s;
    }

    .reset-filter-btn:hover {
        background: #f5f5f5;
    }

    .reset-filter-btn a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    @media (max-width: 576px) {
        .filter-dropdown {
            min-width: 150px;
        }

        .filter-item {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>

<div class="filter-menu-container">
    <button class="filter-btn" id="filterBtn" onclick="toggleFilterMenu()" title="Filter by Financial Year">⋮ Filter</button>
    
    <div class="filter-dropdown" id="filterDropdown">
        <div class="filter-dropdown-header">📅 Filter by Financial Year</div>
        <div class="filter-dropdown-content">
            @foreach($fyOptions as $fy)
                @php
                    $isCurrent = ($fy === $currentFy);
                    $url = $isCurrent 
                        ? route('admin.applicants.select-category')
                        : route('admin.applicants.my-applications', ['fy' => $fy]);
                @endphp
                <div class="filter-item {{ $selectedFy == $fy ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $fy }}@if($isCurrent) <small style="opacity:.7">(current)</small>@endif</a>
                </div>
            @endforeach
        </div>
        @if($selectedFy)
            <div class="reset-filter-btn">
                <a href="{{ route('admin.applicants.select-category') }}">✕ Clear Filter</a>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleFilterMenu() {
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.querySelector('.filter-menu-container');
        if (!container.contains(event.target)) {
            document.getElementById('filterDropdown').classList.remove('show');
        }
    });
</script>

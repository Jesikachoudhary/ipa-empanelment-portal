@extends('layouts.admin_inner')

@section('content')
<div style="color: white !important; padding: 30px 15px !important; margin: -20px -20px 20px -20px !important; position: relative !important; z-index: 10 !important; display: block !important;">
    <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="flex: 1;">
            <h2 style="color: white !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; font-size: 28px !important; display: block !important;">Select Your Applicant Category</h2>
            @php 
                $currentFy = $currentFy ?? \App\Models\Applicant::getCurrentFinancialYear(); 
                $viewFy = $viewFy ?? $currentFy;
            @endphp
            <p style="color: rgba(255, 255, 255, 0.95) !important; margin: 4px 0 0 0 !important; font-size: 15px !important; display: block !important;">
                <strong>Financial Year:</strong> {{ $viewFy }} 
                @if($viewFy !== $currentFy)
                    <span style="background:#fff3cd;color:#856404;font-size:11px;padding:1px 6px;border-radius:3px;">Viewing historical</span>
                @else
                    <span style="opacity:0.8;">(1 Apr {{ explode('-', $viewFy)[0] }} – 31 Mar {{ explode('-', $viewFy)[1] }})</span>
                @endif
            </p>
            <p style="color: rgba(255, 255, 255, 0.9) !important; margin: 6px 0 0 0 !important; display: block !important; font-size: 16px;">Choose the category that best describes your profile to proceed with your application</p>
        </div>
        <div style="margin-left: 20px;">
            @include('partials.filter-menu')
        </div>
    </div>
</div>

<div class="container-fluid" style="padding: 40px 20px;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
            <strong>✓ Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div style="max-width: 100%; margin: 0 auto;">
        @include('partials.category-selector', ['existingApplicant' => $existingApplicant ?? null, 'viewFy' => $viewFy ?? null])

        <div style="margin-top: 60px; padding: 30px; background: rgba(255, 255, 255, 0.05); border-radius: 12px; border-left: 4px solid #667eea; color: black;">
            <h6 style="font-weight: 600; margin-bottom: 10px; color: black;">📋 How This Works</h6>
            <p style="margin: 0; font-size: 14px; line-height: 1.8; opacity: 0.95; color: black;">
                Applications are year-wise (FY: 1 Apr – 31 Mar). Click "Click to Apply for XXXX-XXXX" to apply in the current financial year. Use the ⋮ Filter to view your submissions from previous years (each year is independent).
            </p>
        </div>
    </div>
</div>
@endsection

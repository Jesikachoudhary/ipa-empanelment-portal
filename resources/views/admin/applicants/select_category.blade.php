@extends('layouts.admin_inner')

@section('title', 'Select Category — IPA Empanelment')

@push('styles')
<style>
    .category-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 40px 20px 30px;
        text-align: center;
        margin: -20px -20px 30px;
    }
    .category-hero h2 { font-weight: 700; font-size: 26px; margin-bottom: 6px; }
    .category-hero p  { opacity: .9; font-size: 15px; margin: 0; }

    .category-card {
        border: 2px solid #e0e0e0;
        border-radius: 16px;
        padding: 36px 24px;
        text-align: center;
        transition: all .25s ease;
        cursor: pointer;
        height: 100%;
        background: #fff;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .category-card:hover {
        border-color: #667eea;
        box-shadow: 0 8px 30px rgba(102,126,234,.2);
        transform: translateY(-4px);
        text-decoration: none;
        color: inherit;
    }
    .category-card .icon {
        font-size: 52px;
        margin-bottom: 16px;
        display: block;
    }
    .category-card h4 {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 10px;
        color: #2d2d2d;
    }
    .category-card p {
        font-size: 13px;
        color: #777;
        line-height: 1.6;
        margin: 0;
    }
    .category-card .badge-pill {
        margin-top: 16px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .card-consultant:hover  { border-color: #667eea; }
    .card-young:hover       { border-color: #f093fb; }
    .card-startup:hover     { border-color: #4facfe; }

    .card-consultant .badge-pill  { background: linear-gradient(135deg,#667eea,#764ba2); }
    .card-young .badge-pill       { background: linear-gradient(135deg,#f093fb,#f5576c); }
    .card-startup .badge-pill     { background: linear-gradient(135deg,#4facfe,#00f2fe); }
</style>
@endpush

@section('header')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h2>Apply <small>Select your category</small></h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 text-right">
        <ul class="breadcrumb float-md-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
            <li class="breadcrumb-item active">Select Category</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="category-hero">
    <h2>Expression of Interest — IPA Center of Excellence</h2>
    <p>Please select the category that best describes your profile to proceed with your application.</p>
</div>

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row justify-content-center">

    {{-- Consultant --}}
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <a href="{{ route('admin.applicants.create', ['category' => 'consultant']) }}"
           class="category-card card-consultant">
            <span class="icon">🧑‍💼</span>
            <h4>Consultant</h4>
            <p>Experienced professionals offering advisory, technical, or management consulting services to port and maritime sectors.</p>
            <span class="badge-pill">Apply as Consultant →</span>
        </a>
    </div>

    {{-- Young Professional --}}
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <a href="{{ route('admin.applicants.create', ['category' => 'young_professional']) }}"
           class="category-card card-young">
            <span class="icon">🎓</span>
            <h4>Young Professional</h4>
            <p>Early-career individuals with up to 5 years of experience looking to contribute fresh perspectives to IPA projects.</p>
            <span class="badge-pill" style="background:linear-gradient(135deg,#f093fb,#f5576c);">Apply as Young Professional →</span>
        </a>
    </div>

    {{-- Startup --}}
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <a href="{{ route('admin.applicants.create', ['category' => 'startup']) }}"
           class="category-card card-startup">
            <span class="icon">🚀</span>
            <h4>Startup</h4>
            <p>Innovative startups with technology or service solutions relevant to ports, logistics, and maritime operations.</p>
            <span class="badge-pill" style="background:linear-gradient(135deg,#4facfe,#00f2fe);color:#fff;">Apply as Startup →</span>
        </a>
    </div>

</div>
@endsection

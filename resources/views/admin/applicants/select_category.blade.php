@extends('layouts.admin_inner')

@section('title', 'Select Category — IPA Empanelment')

@push('styles')
<style>
    .cat-hero {
        background: linear-gradient(135deg, #2c3e7a 0%, #4a5568 100%);
        color: #fff;
        padding: 30px 30px 25px;
        margin: -20px -20px 30px;
    }
    .cat-hero h2 {
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 6px;
    }
    .cat-hero .fy {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
        opacity: .95;
    }
    .cat-hero .fy span {
        font-weight: 400;
        opacity: .85;
    }
    .cat-hero p {
        font-size: 14px;
        opacity: .85;
        margin: 0;
    }

    .cat-card {
        border-radius: 16px;
        padding: 40px 28px 32px;
        text-align: center;
        cursor: pointer;
        height: 100%;
        display: block;
        text-decoration: none;
        color: #fff;
        position: relative;
        transition: transform .2s ease, box-shadow .2s ease;
        overflow: hidden;
    }
    .cat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,.25);
        color: #fff;
        text-decoration: none;
    }
    .cat-card .icon {
        font-size: 56px;
        margin-bottom: 16px;
        display: block;
    }
    .cat-card h4 {
        font-weight: 700;
        font-size: 22px;
        margin-bottom: 10px;
        color: #fff;
    }
    .cat-card p {
        font-size: 13px;
        opacity: .85;
        line-height: 1.6;
        margin: 0;
    }
    .cat-card .applied-badge {
        position: absolute;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.5);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .card-consultant  { background: linear-gradient(135deg, #2980b9 0%, #3d6cc0 100%); }
    .card-young       { background: linear-gradient(135deg, #6c5ce7 0%, #a55eea 100%); }
    .card-startup     { background: linear-gradient(135deg, #e84393 0%, #c0392b 100%); }

    .col-cat { margin-bottom: 24px; }
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

<div class="cat-hero">
    <h2>Select Your Applicant Category</h2>
    <div class="fy">Financial Year: 2026-2027 <span>(1 Apr 2026 – 31 Mar 2027)</span></div>
    <p>Choose the category that best describes your profile to proceed with your application</p>
</div>

@if(session('success') || session('status'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    <strong>✓ SUCCESS!</strong> {{ session('success') ?? session('status') }}
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    {{ session('info') }}
</div>
@endif

@php
    $existing = auth('admin')->user()->applicant ?? null;
    $appliedCategories = $existing ? (array)($existing->categories ?? []) : [];
    $hasApplied = !empty($appliedCategories);
@endphp

<div class="row">

    {{-- Consultant --}}
    <div class="col-lg-4 col-md-6 col-sm-12 col-cat">
        <a href="{{ route('admin.applicants.create', ['category' => 'consultant']) }}"
           class="cat-card card-consultant">
            <span class="icon">👔</span>
            <h4>Consultant</h4>
            <p>Independent consultant or consulting professional</p>
            @if($hasApplied)
                <span class="applied-badge">✓ Applied for 2026-2027</span>
            @endif
        </a>
    </div>

    {{-- Young Professional --}}
    <div class="col-lg-4 col-md-6 col-sm-12 col-cat">
        <a href="{{ route('admin.applicants.create', ['category' => 'young_professional']) }}"
           class="cat-card card-young">
            <span class="icon">🚀</span>
            <h4>Young Professional</h4>
            <p>Early career professional with passion for growth</p>
            @if($hasApplied)
                <span class="applied-badge">✓ Applied for 2026-2027</span>
            @endif
        </a>
    </div>

    {{-- Startup --}}
    <div class="col-lg-4 col-md-6 col-sm-12 col-cat">
        <a href="{{ route('admin.applicants.create', ['category' => 'startup']) }}"
           class="cat-card card-startup">
            <span class="icon">💡</span>
            <h4>Startup</h4>
            <p>Innovative startup with technology solutions for ports & logistics</p>
            @if($hasApplied)
                <span class="applied-badge">✓ Applied for 2026-2027</span>
            @endif
        </a>
    </div>

</div>
@endsection

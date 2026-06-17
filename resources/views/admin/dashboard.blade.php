@extends('layouts.admin_inner')

@section('title','Admin Dashboard')

@section('header')
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Dashboard <small>Welcome back</small></h2>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> {{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
@php
    $currentFy = \App\Models\Applicant::getCurrentFinancialYear();
    $isSuper = auth('admin')->user()->is_super ?? false;
    $totalApplicants = \App\Models\Applicant::where('application_fy', $currentFy)->count();
    $consultants     = \App\Models\Applicant::where('application_fy', $currentFy)->where('applicant_type', 'consultant')->count();
    $youngProfs      = \App\Models\Applicant::where('application_fy', $currentFy)->where('applicant_type', 'young_professional')->count();
    $startups        = \App\Models\Applicant::where('application_fy', $currentFy)->where('applicant_type', 'startups')->count();
    $totalAdmins     = \App\Models\Admin::count();
@endphp

<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-12 text-center">
                        <h4>Welcome, {{ auth('admin')->user()->name ?? 'Admin' }}!</h4>
                        <p class="text-muted">Financial Year: <strong>{{ $currentFy }}</strong></p>
                    </div>
                </div>

                @if($isSuper)
                {{-- Super admin: full stats --}}
                <div class="row m-t-20 text-center">
                    <div class="col-md-3 col-sm-6">
                        <div class="card" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:12px;padding:20px;">
                            <h2 style="font-size:36px;font-weight:700;margin:0;">{{ $totalApplicants }}</h2>
                            <p style="margin:4px 0 0;opacity:.9;">Total Applicants</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card" style="background:linear-gradient(135deg,#2980b9,#3d6cc0);color:#fff;border-radius:12px;padding:20px;">
                            <h2 style="font-size:36px;font-weight:700;margin:0;">{{ $consultants }}</h2>
                            <p style="margin:4px 0 0;opacity:.9;">👔 Consultants</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card" style="background:linear-gradient(135deg,#6c5ce7,#a55eea);color:#fff;border-radius:12px;padding:20px;">
                            <h2 style="font-size:36px;font-weight:700;margin:0;">{{ $youngProfs }}</h2>
                            <p style="margin:4px 0 0;opacity:.9;">🚀 Young Professionals</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card" style="background:linear-gradient(135deg,#e84393,#c0392b);color:#fff;border-radius:12px;padding:20px;">
                            <h2 style="font-size:36px;font-weight:700;margin:0;">{{ $startups }}</h2>
                            <p style="margin:4px 0 0;opacity:.9;">💡 Startups</p>
                        </div>
                    </div>
                </div>

                <div class="row m-t-20 text-center">
                    <div class="col-md-4 col-sm-6">
                        <div class="card" style="border-radius:12px;padding:20px;border:2px solid #eee;">
                            <h2 style="font-size:32px;font-weight:700;color:#2d3436;">{{ $totalAdmins }}</h2>
                            <p class="text-muted" style="margin:4px 0 0;">Registered Admins</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card" style="border-radius:12px;padding:20px;border:2px solid #eee;">
                            <h2 style="font-size:32px;font-weight:700;color:#2d3436;">{{ $currentFy }}</h2>
                            <p class="text-muted" style="margin:4px 0 0;">Active Financial Year</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card" style="border-radius:12px;padding:20px;border:2px solid #eee;">
                            <a href="{{ route('admin.applicants.index') }}" class="btn btn-primary btn-block" style="margin-top:4px;">View All Applicants</a>
                        </div>
                    </div>
                </div>
                @else
                {{-- Regular admin: show their own application status --}}
                @php
                    $myApps = \App\Models\Applicant::where('admin_id', auth('admin')->id())
                        ->where('application_fy', $currentFy)->get()->keyBy('applicant_type');
                @endphp
                <div class="row m-t-20 justify-content-center">
                    @forelse($myApps as $type => $app)
                    <div class="col-md-4 col-sm-6 text-center mb-3">
                        <div class="card" style="border-radius:12px;padding:20px;border:2px solid #28a745;">
                            <span style="font-size:32px;">
                                @if($type === 'consultant') 👔
                                @elseif($type === 'young_professional') 🚀
                                @else 💡 @endif
                            </span>
                            <h5 style="margin:10px 0 4px;font-weight:600;">{{ ucwords(str_replace('_',' ',$type)) }}</h5>
                            <span class="badge badge-success">✓ Applied {{ $currentFy }}</span>
                            <br>
                            <a href="{{ route('admin.applicants.edit', $app) }}" class="btn btn-sm btn-outline-primary mt-2">View/Edit</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-8 text-center">
                        <p class="text-muted">You have not submitted an application for {{ $currentFy }} yet.</p>
                        <a href="{{ route('admin.applicants.select-category') }}" class="btn btn-primary">Apply Now</a>
                    </div>
                    @endforelse
                </div>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('admin.password.change') }}" class="btn btn-info">Change Password</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline ml-2">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

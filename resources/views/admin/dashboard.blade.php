@extends('layouts.admin_inner')

@section('title','Admin Dashboard')

@section('header')
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Dashboard
            <small>Welcome back</small>
            </h2>
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
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 text-center">
                            <h4>Welcome, {{ auth('admin')->user()->name ?? 'Admin' }}!</h4>
                            <p class="text-muted">Use the sidebar to navigate the admin sections.</p>
                        </div>
                    </div>
                     @if(auth('admin')->check() && auth('admin')->user()->is_super)
                    <div class="row m-t-20">
                        <div class="col-md-4 text-center">
                            <div class="body">
                                <h2 class="number">{{ \App\Models\Admin::count() }}</h2>
                                <p class="text-muted">Admins</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="body">
                                <h2 class="number">—</h2>
                                <p class="text-muted">Placeholder Metric</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="body">
                                <h2 class="number">—</h2>
                                <p class="text-muted">Placeholder Metric</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-3 text-center">
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

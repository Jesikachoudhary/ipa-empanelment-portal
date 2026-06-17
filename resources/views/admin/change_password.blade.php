@extends('layouts.admin_inner')

@section('title','Change Password')

@section('header')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h2>Change Password</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Change</strong> Password</h2>
                </div>
                <div class="body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.password.change.post') }}">
                        @csrf

                        <div class="input-group">
                            <input id="current_password" type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <br>

                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control" placeholder="New Password" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <br>

                        <div class="input-group">
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-round">Change Password</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-default btn-round">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

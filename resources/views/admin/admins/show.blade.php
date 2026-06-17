@extends('layouts.admin_inner')

@section('title','Admin Details')

@section('header')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h2>Admin Details</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="card">
                <div class="header">
                    <h2>{{ $admin->name }}</h2>
                </div>
                <div class="body">
                    <p><strong>Email:</strong> {{ $admin->email }}</p>
                    <p><strong>Is Super:</strong> {{ $admin->is_super ? 'Yes' : 'No' }}</p>
                    <p><strong>Created At:</strong> {{ $admin->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $admin->updated_at }}</p>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

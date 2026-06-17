@extends('layouts.admin_inner')

@section('title','Admins')

@section('header')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h2>Admins</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-12">
            <div class="card">
                <div class="header">
                    <h2>All Admins</h2>
                </div>
                <div class="body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Super</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($admins as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->name }}</td>
                                <td>{{ $a->email }}</td>
                                <td>{{ $a->is_super ? 'Yes' : 'No' }}</td>
                                <td>{{ $a->created_at }}</td>
                                <td><a href="{{ route('admin.admins.show', $a->id) }}" class="btn btn-sm btn-primary">View</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

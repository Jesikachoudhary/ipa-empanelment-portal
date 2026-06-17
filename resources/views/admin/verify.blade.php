@extends('layouts.admin')

@section('title','Verify Email')

@section('content')
<div class="page-header">
    <div class="page-header-image" style="background-image:url('/html/assets/images/login.jpg')"></div>
    <div class="container">
        <div class="col-md-6 content-center">
            <div class="card-plain">
                @if(session('status'))
                    <div class="alert alert-success" id="notification">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" id="notification">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.verify.post') }}">
                    @csrf
                    <div class="header">
                        <h5>Verify Your Email</h5>
                        <span>Enter the 6-digit code sent to your email</span>
                    </div>
                    <div class="content">
                        <div class="input-group input-lg">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $email ?? '') }}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                        </div>

                        <div class="input-group input-lg">
                            <input type="text" name="code" class="form-control" placeholder="6-digit Code" value="{{ old('code', $code ?? '') }}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
                        </div>
                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">VERIFY</button>
                    </div>
                </form>

                 <button type="button" class="btn btn-primary btn-round btn-lg btn-block"><a style="color:white;" href="{{ route('admin.login') }}">Back to login</a></button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    // Ensure only one notification is shown at a time
    document.addEventListener('DOMContentLoaded', function() {
        const notifications = document.querySelectorAll('[id="notification"]');
        if (notifications.length > 1) {
            // Keep only the first notification, remove others
            for (let i = 1; i < notifications.length; i++) {
                notifications[i].remove();
            }
        }
    });
</script>
@extends('layouts.admin')

@section('title','Admin Login')

@section('content')
<div class="page-header">
    <div class="page-header-image" style="background-image:url('/html/assets/images/login.jpg')"></div>
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="card-plain">
                @if(session('status'))
                    <div class="alert alert-success" id="notification">{{ session('status') }}</div>
                @endif
                @if(session('show_verify'))
                    <div class="alert alert-warning" id="notification">
                        Your account is not verified. Please verify your email before logging in.
                        @php $prefillEmail = old('email') ?? '' ; @endphp
                        <div class="mt-2">
                            <a href="{{ route('admin.verify', ['email' => $prefillEmail]) }}" class="btn btn-sm btn-outline-primary">Open Verify Page</a>
                        </div>
                    </div>
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
                <form class="form" method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="header">
                        <h5>Log in</h5>
                    </div>
                    <div class="content">
                        <div class="input-group input-lg">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-account-circle"></i>
                            </span>
                        </div>
                        <div class="input-group input-lg">
                            <input type="password" name="password" placeholder="Password" class="form-control" required />
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                       <!-- <div class="checkbox">
                            <label><input type="checkbox" name="remember"> Remember Me</label>
                        </div>-->
                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">SIGN IN</button>
                        <h5><a href="{{ route('admin.password.request') }}" class="link">Forgot Password?</a></h5>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <nav>
                <ul>
                    <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=2">Contact Us</a></li>
                    <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=341">About Us</a></li>
                    
                </ul>
            </nav>
        </div>
    </footer>
</div>

@endsection
@extends('layouts.admin')

@section('title','Reset Password')

@section('content')
<div class="page-header">
    <div class="page-header-image" style="background-image:url('/html/light/assets/images/login.jpg')"></div>
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="card-plain">
                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="header">
                        <div class="logo-container">
                            <img src="/html/assets/images/logo.svg" alt="">
                        </div>
                        <h5>Reset Password</h5>
                    </div>
                    <div class="content">
                        <div class="input-group input-lg">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $email ?? old('email') }}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                        </div>
                        <div class="input-group input-lg">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                        </div>
                        <div class="input-group input-lg">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                        </div>
                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">Reset Password</button>
                    </div>
                </form>
                <p class="m-t-20"><a href="{{ route('admin.login') }}">Back to login</a></p>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <nav>
                <ul>
                    <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=2" target="_blank">Contact Us</a></li>
                    <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=341" target="_blank">About Us</a></li>
                   
                </ul>
            </nav>
            <div class="copyright">
                &copy;
                <script>document.write(new Date().getFullYear())</script>,
                <span>Designed by <a href="http://thememakker.com/" target="_blank">ThemeMakker</a></span>
            </div>
        </div>
    </footer>
</div>
@endsection

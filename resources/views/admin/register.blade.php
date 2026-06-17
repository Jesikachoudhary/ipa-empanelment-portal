@extends('layouts.admin')

@section('title','Admin Register')

@section('content')
<div class="page-header">
    <div class="page-header-image" style="background-image:url('/html/light/assets/images/login.jpg')"></div>
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="card-plain">
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

                <form method="POST" action="{{ route('admin.register.post') }}">
                    @csrf
                        <div class="header">
                        <h5>Register</h5>
                    </div>
                    <div class="content">
                        <div class="input-group input-lg">
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                        </div>
                        <div class="input-group input-lg">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
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
                        <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">REGISTER</button>
                    </div>
                </form>
               <!-- <p class="m-t-20"><a href="{{ route('admin.login') }}">Back to login</a></p>-->
                 <button type="button" class="btn btn-primary btn-round btn-lg btn-block"><a style="color:white;" href="{{ route('admin.login') }}">Back to login</a></button>
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
                <span>Designed by <a href="https://www.ipa.nic.in/" target="_blank">IPA</a></span>
            </div>
        </div>
    </footer>
</div>
@endsection

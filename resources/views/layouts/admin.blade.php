<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Admin Panel">
    <title>@yield('title', config('app.name') . ' - Admin')</title>
    <link rel="icon" href="/html/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/html/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/html/light/assets/css/main.css">
    <link rel="stylesheet" href="/html/light/assets/css/authentication.css">
    <link rel="stylesheet" href="/html/light/assets/css/color_skins.css">
    @stack('styles')
</head>
<body class="theme-purple authentication sidebar-collapse">

<!-- Navbar (from theme) -->
<nav class="navbar navbar-expand-lg fixed-top navbar-transparent">
    <div class="container">
        <div class="navbar-translate n_logo">
            <a class="navbar-brand" href="{{ route('admin.home') }}" title="" target="_self">IPA</a>
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.home') }}">Home</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" title="Follow us on Twitter" href="https://x.com/Indportsassn" target="_blank">
                        <i class="zmdi zmdi-twitter"></i>
                        <p class="d-lg-none d-xl-none">Twitter</p>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" title="Like us on Facebook" href="javascript:void(0);" target="_blank">
                        <i class="zmdi zmdi-facebook"></i>
                        <p class="d-lg-none d-xl-none">Facebook</p>
                    </a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" title="Follow us on Instagram" href="https://www.instagram.com/indportsassn/" target="_blank">                        
                        <i class="zmdi zmdi-instagram"></i>
                        <p class="d-lg-none d-xl-none">Instagram</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-white btn-round" href="{{ route('admin.register') }}">SIGN UP</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<script src="/html/light/assets/bundles/libscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/vendorscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/mainscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/morrisscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/mainscripts.bundle.js"></script>
@stack('scripts')
@stack('scripts')
</body>
</html>

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
    <link rel="stylesheet" href="/html/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css">
    <link rel="stylesheet" href="/html/assets/plugins/morrisjs/morris.min.css">
    <link rel="stylesheet" href="/html/light/assets/css/main.css">
    <link rel="stylesheet" href="/html/light/assets/css/color_skins.css">
    @stack('styles')
</head>
<body class="theme-purple">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="/html/light/assets/images/logo.svg" width="48" height="48" alt="{{ config('app.name') }}"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>

@include('partials.admin_topbar')

@include('partials.admin_sidebar')

@includeIf('partials.admin_rightsidebar')

<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        @yield('header')
    </div>
    <div class="container-fluid">
        @yield('content')
    </div>
</section>

<!-- Chat launcher and wrapper (optional placeholder) -->
@stack('after-sidebars')

<!-- Jquery Core Js -->
<script src="/html/light/assets/bundles/libscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/vendorscripts.bundle.js"></script>
<script src="/html/light/assets/bundles/mainscripts.bundle.js"></script>
<script src="/html/light/assets/js/pages/index.js"></script>
@stack('scripts')
</body>
</html>

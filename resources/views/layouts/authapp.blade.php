<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Laravel')</title>
    
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        
        {{-- Theme css libraury --}}
        <link rel="stylesheet" href="{{asset('assets/vendors/feather/feather.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
        <!-- inject:css -->
        <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
        <!-- endinject -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />
        
        @yield('styles')
    </head>

    <body>
        <div class="container-scroller">            
            @yield('login_body')
        </div>
       

        {{-- Theme scripts libraury --}}
        <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
        <!-- inject:js -->
        <script src="{{asset('assets/js/off-canvas.js')}}"></script>
        <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
        <script src="{{asset('assets/js/template.js')}}"></script>
        <script src="{{asset('assets/js/settings.js')}}"></script>
        <script src="{{asset('assets/js/todolist.js')}}"></script>
        <script src="{{asset('assets/js/jQuery.js')}}"></script>
        <!-- endinject -->
        <!-- End Theme scripts libraury-->

        @yield('scripts')
        @include('vendor.javascript_includes.vendor_auth_script');
    </body>
</html>
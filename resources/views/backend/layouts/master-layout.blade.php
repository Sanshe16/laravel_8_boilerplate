<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <!-- Url Path -->
            <meta name="server-path" content="{{ url('/') }}">

        <title>@yield('title', 'Gifter')</title>


        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        {{-- Theme css libraury --}}
        @include('backend.css_includes.theme_css')

        {{-- customes css file include  --}}
        @include('backend.css_includes.custom_css')


        @yield('styles')
    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            @include('backend.partial.header')
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">

                <!-- partial:partials/_sidebar.html -->
                @include('backend.partial.sidebar')
                <!-- partial -->
                <div class="main-panel">

                    <!-- body panel start  -->
                    <div class="content-wrapper">
                         @yield('body')
                    </div>
                    <!-- body panel start  -->

                <!-- partial:partials/_footer.html -->
                    @include('backend.partial.footer')
                </div>
                <!-- partial -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->

        {{-- Theme scripts libraury --}}
        @include('backend.javascript_includes.theme_script')

        {{-- customes scripts file include  --}}
        @include('backend.javascript_includes.custom_script')

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
        <!-- End custom js for this page-->

        @yield('scripts')
    </body>
</html>

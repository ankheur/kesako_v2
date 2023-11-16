<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('build/img/favicon.ico') }}" type="image/x-icon">

    @filamentStyles
    {{--@vite('resources/css/app.css')--}}

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/slick/slick-theme.css') }}">

    <!-- icon css-->
    <link rel="stylesheet" href="{{ asset('build/assets/elagent-icon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/niceselectpicker/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/animation/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/mcustomscrollbar/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/responsive.css') }}">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body data-scroll-animation="true">
    <x-preloader/>

    <div class="body_wrapper">
        <x-navigation :categories="$categories"/>
        <x-banner />

        {{ $slot }}

        <x-footer />
    </div>

    <!-- Back to top button -->
    <x-backtotop/>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('build/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('build/assets/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('build/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('build/js/pre-loader.js') }}"></script>
    <script src="{{ asset('build/assets/slick/slick.min.js') }}"></script>
    <script src="{{ asset('build/js/jquery.parallax-scroll.js') }}"></script>
    <script src="{{ asset('build/assets/niceselectpicker/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('build/assets/wow/wow.min.js')}}"></script>
    <script src="{{ asset('build/assets/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('build/assets/magnify-pop/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('build/js/plugins.js') }}"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <script src="{{ asset('build/js/main.js') }}"></script>
</body>
</html>

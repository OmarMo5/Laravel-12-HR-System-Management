<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Sign In | ASC Hr System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="ASC HR System" name="description">
    <meta content="Omar Mokhtar" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::to('assets/images/favicon.ico') }}">
    <!-- Layout config Js -->
    <script src="{{ URL::to('assets/js/layout.js') }}"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- StarCode CSS -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/starcode2.css') }}">

    <style>
        body { font-family: 'Tajawal', sans-serif !important; }
    </style>
</head>

@if(Request::is('login') || Request::is('/'))
<body class="bg-white dark:bg-zink-900 min-h-screen w-full">
    @yield('content')
@else
<body class="flex items-center justify-center min-h-screen py-16 lg:py-10 bg-slate-50 dark:bg-zink-800 dark:text-zink-100 font-public">
    <div class="relative">
        <div class="absolute hidden opacity-50 ltr:-left-16 rtl:-right-16 -top-10 md:block">
            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 125 316" width="125" height="316">
                <g><path class="fill-custom-100/50 dark:fill-custom-950/50" d="m23.4 221.8l-1.3-3.1v-315.3l1.3 3.1z"></path>
                <path class="fill-custom-100 dark:fill-custom-950" d="m31.2 229.6l-1.3-3.1v-315.3l1.3 3.1z"></path>
                <path class="fill-custom-500/50 dark:fill-custom-600/50" d="m109.3 307.8l-1.3-3.1v-315.4l1.3 3.1z"></path></g>
            </svg>
        </div>
        @yield('content')
    </div>
@endif

    <script src="{{ URL::to('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::to('assets/libs/lucide/umd/lucide.js') }}"></script>
    <script src="{{ URL::to('assets/js/starcode.bundle.js') }}"></script>
    @yield('script')
</body>
</html>

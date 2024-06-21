<!doctype html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kos Pangeran</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="{{asset('admin')}}/css/style.css" rel="stylesheet">

</head>
<body class="h-100" style="background-color: rgb(17 24 39);">
    <div id="app" class="h-100">
        @yield('content')
    </div>


    <script src="{{asset('admin')}}/vendor/global/global.min.js"></script>
    <script src="{{asset('admin')}}/js/quixnav-init.js"></script>
    <script src="{{asset('admin')}}/js/custom.min.js"></script>
</body>
</html>

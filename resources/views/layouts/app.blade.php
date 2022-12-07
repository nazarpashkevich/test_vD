<!doctype html>
<html lang="{{ \App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>{{$title}}</title>
</head>
<body>
<main>
    <div class="main-bg-container">
        <div class="main-bg-blur-layer"></div>
    </div>
    <div class="main-content-container">
        <div class="content-block">
            @yield('content')
        </div>
    </div>
</main>
@vite('resources/js/app.js')
</body>
</html>

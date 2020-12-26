<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <!-- Latest compiled and minified CSS & JS -->
        <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
        @yield('extrastyles')
    </head>
    <body>
        <div id="app">
            @yield('navbar')
            <div class="container">
                @yield('content')
            </div>
        </div>

     @yield('pagescript')
    </body>
</html>
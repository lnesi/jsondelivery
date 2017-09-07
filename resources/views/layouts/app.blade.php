<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="devkey" value="UkdWMlpXeHZjR1ZrSUVKNUlFeDFhWE1nVG1WemFTQXRJR3h1WlhOcExtZHBkR2gxWWk1cGJ3PT0=">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <script src="https://use.fontawesome.com/305feb8e9e.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/linearicons.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <preloader></preloader>
        <mainnav ></mainnav>
        <alert></alert>
        <confirm></confirm>
        <div class="contentHolder">
        @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script>
        var currentUser={!!Auth::user()->toJson()!!};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        console.log('DEV Key','UkdWMlpXeHZjR1ZrSUVKNUlFeDFhWE1nVG1WemFTQXRJR3h1WlhOcExtZHBkR2gxWWk1cGJ3PT0=');
    </script>
</body>
</html>

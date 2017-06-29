<!DOCTYPE html>
<html lang="de">
<head>
    <title>@yield('title')wpkg-viewer</title>
    <meta charset="UTF-8" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/css/font-awesome.css') }}" />

    @section('head')
    @show
</head>
<body>
    <div class="wrapper">
        @yield('content')
    </div>
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    @section('scripts')
    @show
</body>
</html>

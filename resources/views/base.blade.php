<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>SIESTA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Meta data --}}
    <meta http-equiv="content-language" content="es">

    {{-- End Meta data --}}
    @section('styles')
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @show
</head>
<body>
<div class="wrapper">
    @include('header')
    @yield('content')
    @include('footer')
</div>

@section('footer-scripts')
    <script type="text/javascript" src="{{ asset('js/app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/custom.js')}}"></script>
@show
</body>
</html>



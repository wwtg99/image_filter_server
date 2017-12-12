<!DOCTYPE html>
<html lang="{{ __(config('app.locale')) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/image_filter/images/favicon.ico" />

    <title>@section('title') {{ __(config('app.name')) }} - {{ __($title) }} @show</title>

    <!-- Styles -->
    @section('css')
        {{--<link rel="stylesheet" href="/css/app.css">--}}
    @show
</head>
<body>
@section('center')
    <div id="app">
        <image-filter-home></image-filter-home>
    </div>
@show
<!-- Javascript -->
@section('js')
    <script src="/assets/image_filter/js/app.js"></script>
@show
</body>
</html>
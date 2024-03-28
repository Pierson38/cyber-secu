@php use Illuminate\Support\Facades\Vite; @endphp
    <!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRM 5.3</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@include("layouts.header")

<div class="container">
    @yield('content')
</div>

@include("layouts.footer")
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test - @yield('title', 'Home')</title>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-neutral-950">

    @if (!Request::is('login', 'register'))
        @include('layouts._header')
    @endif

    @yield('content')

    <x-toaster-hub />
    @livewireScripts
</body>

</html>
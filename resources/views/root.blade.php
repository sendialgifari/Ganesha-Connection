<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ URL::asset('images/favicon.ico') }}" type="image/x-icon"/>
        @spladeHead
        <link rel="preload" as="style" href="{{ URL::asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
        <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css">
        @vite('resources/js/app.js')
        {{-- <script defer src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script> --}}
    </head>
    <body class="font-sans antialiased">
        @splade
    </body>
</html>

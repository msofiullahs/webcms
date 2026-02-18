<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme" content="{{ config('app.theme', 'default') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Load active theme CSS files --}}
    @foreach (config('app.theme_css_files', []) as $cssFile)
        <link rel="stylesheet" href="{{ $cssFile }}">
    @endforeach
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>

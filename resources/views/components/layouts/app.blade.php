<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kerajinan Tangan' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-base-200 bg-grid-pattern">
    @livewire('header')
    @livewire('mobile-bottom-navigation')

    <main class="flex-grow pb-16 sm:pb-0">
        {{ $slot }}
    </main>

    @livewire('footer')
</body>
</html>
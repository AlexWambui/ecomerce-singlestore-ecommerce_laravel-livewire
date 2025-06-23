<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        {{-- Vite Assets --}}
        @vite(['resources/css/app-layout.css', 'resources/js/app.js'])

        {{-- Livewire --}}
        @livewireStyles

        {{-- Dynamic Slot/Stacked Head --}}
        @isset($head)
            {{ $head }}
        @else
            <title>{{ config('app.name') }}</title>
        @endisset
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        <livewire:partials.app-navbar />

        <livewire:partials.flash-messages />

        <div class="app_layout">
            {{-- Page Content --}}
            {{ $slot ?? '' }}
            @yield('content')
        </div>

        {{-- Livewire --}}
        @livewireScripts

        {{-- Dynamic Scripts --}}
        @isset($scripts)
            {{ $scripts }}
        @endisset
        @stack('scripts')

        @isset($afterScripts)
            {{ $afterScripts }}
        @endisset
        @stack('after-scripts')
    </body>
</html>

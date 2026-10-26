<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-appbg">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskFlow') }} — Smart To-Do List & Productivity Dashboard</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased text-textmain bg-appbg flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-md space-y-6">
            <div class="flex justify-center">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="bg-surface p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-md">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-textsec">
                &copy; {{ date('Y') }} TaskFlow. Smart Task Management & Productivity Dashboard.
            </p>
        </div>
    </body>
</html>

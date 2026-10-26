<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-appbg">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - TaskFlow' : 'TaskFlow — Smart To-Do List & Productivity' }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts and Styles via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="h-full antialiased font-sans bg-appbg text-textmain" x-data="{ mobileMenuOpen: false }">
        <div class="min-h-full flex flex-col lg:flex-row">
            
            <!-- Mobile Sidebar Backdrop & Drawer -->
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 class="relative z-50 lg:hidden" 
                 role="dialog" 
                 aria-modal="true">
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
                     @click="mobileMenuOpen = false"></div>

                <div class="fixed inset-0 flex">
                    <div x-show="mobileMenuOpen"
                         x-transition:enter="transition ease-in-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in-out duration-300 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         class="relative mr-16 flex w-full max-w-xs flex-1">
                        
                        <div class="flex flex-col grow bg-surface border-r border-gray-200 px-6 pb-6 pt-5 shadow-2xl">
                            <!-- Logo & Close -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white shadow-sm font-bold text-lg">
                                        TF
                                    </div>
                                    <span class="text-xl font-bold tracking-tight text-textmain">Task<span class="text-primary">Flow</span></span>
                                </a>
                                <button type="button" @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mobile Create Task Button -->
                            <div class="mt-6">
                                <a href="{{ route('tasks.create') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-primary hover:bg-primary-600 text-white font-medium shadow-sm transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Task
                                </a>
                            </div>

                            <!-- Mobile Navigation Links -->
                            <nav class="mt-6 flex flex-1 flex-col space-y-1">
                                @include('layouts.sidebar-links')
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Static Sidebar -->
            <aside class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 bg-surface border-r border-gray-200/80 z-20">
                <div class="flex flex-col grow pt-5 pb-4 overflow-y-auto px-5">
                    <!-- Brand Logo -->
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white shadow-sm font-bold text-lg">
                            TF
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-textmain">Task<span class="text-primary">Flow</span></span>
                            <span class="block text-[11px] font-medium text-textsec uppercase tracking-wider">Productivity Hub</span>
                        </div>
                    </div>

                    <!-- Prominent "Create Task" Button -->
                    <div class="mt-6">
                        <a href="{{ route('tasks.create') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-primary hover:bg-primary-600 text-white font-medium shadow-sm shadow-primary/25 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Task
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="mt-6 flex-1 space-y-1.5">
                        @include('layouts.sidebar-links')
                    </nav>

                    <!-- User Footer in Sidebar -->
                    <div class="pt-4 border-t border-gray-100 mt-auto">
                        <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-gray-50 transition">
                            <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-semibold flex items-center justify-center text-sm border border-primary/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-textmain truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-textsec truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area with Header -->
            <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
                <!-- Top Navigation Bar -->
                <header class="sticky top-0 z-10 flex h-16 bg-surface/90 backdrop-blur-md border-b border-gray-200/80 px-4 sm:px-6 lg:px-8 items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button type="button" @click="mobileMenuOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-textmain truncate">
                            {{ $header ?? 'TaskFlow' }}
                        </h1>
                    </div>

                    <!-- Right Quick Actions -->
                    <div class="flex items-center gap-3">
                        <!-- Quick Add Task (Desktop) -->
                        <a href="{{ route('tasks.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Task
                        </a>

                        <!-- Notifications Bell -->
                        @php
                            $unreadCount = Auth::user()->unreadNotificationsCount();
                        @endphp
                        <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-500 hover:text-textmain hover:bg-gray-100 rounded-xl transition" title="Notifikasi">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-danger text-[10px] font-bold text-white">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary text-white font-medium flex items-center justify-center text-xs shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span class="hidden md:inline-block text-sm font-medium text-textmain">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-surface rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-textsec">Signed in as</p>
                                    <p class="text-sm font-semibold text-textmain truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-textmain hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-textsec" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile & Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-danger hover:bg-red-50 transition">
                                        <svg class="w-4 h-4 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Alerts & Toast Notifications -->
                <div class="px-4 sm:px-6 lg:px-8 pt-4">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-xs transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl shadow-xs">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold">Terdapat beberapa kesalahan:</h4>
                                    <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Page Content Slot -->
                <main class="flex-1 pb-12">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>

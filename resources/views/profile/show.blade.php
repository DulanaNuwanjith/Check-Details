<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Account Settings | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="bg-[#f8fafc] text-slate-900 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar />

<!-- Main Content Area -->
<div class="flex-1 flex flex-col h-screen overflow-hidden">
    
    <!-- Premium Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-10 py-6 sticky top-0 z-10">
        <div>
            <h1 class="font-black text-3xl text-slate-800 tracking-tight">Account Settings</h1>
            <p class="text-slate-500 text-sm font-medium mt-1 uppercase tracking-widest text-[10px]">Security, Identity & Privacy Management</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </header>

    <!-- Scrollable Content -->
    <main class="flex-1 overflow-y-auto p-10 bg-slate-50/30">
        <div class="max-w-5xl mx-auto space-y-12 pb-24">
            
            <!-- Update Profile Info Section -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            <!-- Update Password Section -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-100">
                    @livewire('profile.update-password-form')
                </div>
            @endif

            <!-- Two Factor Auth Section -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-200">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            <!-- Browser Sessions Section -->
            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-300">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <!-- Delete Account Section -->
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-400">
                    @livewire('profile.delete-user-form')
                </div>
            @endif

        </div>
    </main>
</div>

@livewireScripts
@stack('modals')
</body>
</html>

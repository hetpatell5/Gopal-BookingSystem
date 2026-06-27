<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Setu Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f4f5f1] antialiased text-gray-900 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navbar -->
        <header class="bg-white h-[72px] flex items-center justify-between px-8 shrink-0 shadow-sm z-10">
            <!-- Page Title -->
            <h1 class="text-[20px] font-bold text-[#1c2238] tracking-tight min-w-[200px]">@yield('header', 'Dashboard')
            </h1>

            <!-- Global Search Bar -->
            <div class="flex-1 max-w-xl px-8 hidden md:block">
                <form action="{{ route('passengers.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i
                            class="fa-solid fa-search text-gray-400 group-focus-within:text-[#f0b44b] transition-colors"></i>
                    </div>
                    <input type="text" name="search" placeholder="Global search passengers, mobile, or seat..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 focus:bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#f0b44b] focus:border-[#f0b44b] sm:text-sm transition-colors shadow-sm">
                </form>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-6">
                <!-- Date -->
                <span class="text-[13px] font-semibold text-gray-500 hidden lg:block">
                    <i class="fa-regular fa-calendar mr-2"></i>{{ now()->format('l, d F Y') }}
                </span>

                <!-- Notifications -->
                <button class="text-gray-400 hover:text-[#f0b44b] transition-colors relative">
                    <i class="fa-regular fa-bell text-[18px]"></i>
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                    </span>
                </button>

                <!-- User Dropdown (Logout) -->
                @auth
                    <div class="relative flex items-center gap-3 pl-6 border-l border-gray-200">
                        <div
                            class="w-8 h-8 rounded-full bg-[#f0b44b] text-[#1c2238] flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[13px] font-bold text-[#1c2238]">{{ auth()->user()->name ?? 'Admin User' }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-[11px] font-semibold text-red-500 hover:text-red-700 text-left transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

</body>

</html>
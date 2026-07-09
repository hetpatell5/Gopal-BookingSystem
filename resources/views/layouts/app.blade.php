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
    
    @if(file_exists(public_path('favicon.ico')))
        <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    @endif

    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>


    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Sidebar Collapsed Styles */
        aside.collapsed-sidebar {
            width: 80px !important;
        }
        aside.collapsed-sidebar .sidebar-text {
            display: none !important;
        }
        aside.collapsed-sidebar a, aside.collapsed-sidebar .user-footer {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        aside.collapsed-sidebar a svg {
            margin-right: 0 !important;
        }
        aside.collapsed-sidebar .logo-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            justify-content: center !important;
        }
        aside.collapsed-sidebar .user-avatar {
            margin-right: 0 !important;
        }

        /* TomSelect Override & Custom Styling */
        .ts-wrapper {
            padding: 0 !important;
            border: none !important;
        }
        .ts-control {
            border: 1px solid #e5e7eb !important; /* border-gray-200 */
            border-radius: 0 !important; /* rounded-none */
            padding: 0.5rem 1rem !important; /* py-2 px-4 */
            padding-right: 2.5rem !important;
            font-size: 14px !important;
            box-shadow: none !important;
            background-color: #fff !important;
            color: #1c2238 !important;
            min-height: 42px;
            display: flex;
            align-items: center;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #f0b44b !important;
            box-shadow: 0 0 0 2px rgba(240, 180, 75, 0.2) !important;
        }
        
        /* Hide TomSelect default arrow */
        .ts-wrapper.single .ts-control::after {
            display: none !important;
        }
        
        /* Add FontAwesome Dropdown Icon */
        .ts-wrapper::before {
            content: '\f078' !important; /* fa-chevron-down */
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
            position: absolute !important;
            right: 14px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            pointer-events: none !important;
            color: #9ca3af !important;
            font-size: 14px !important;
            z-index: 2 !important;
        }
        .ts-wrapper.focus::before {
            color: #f0b44b !important;
        }
        .ts-dropdown {
            border-radius: 0 !important;
            border-color: #e5e7eb !important;
            font-size: 14px !important;
        }
    </style>
</head>
<body class="bg-[#f4f5f1] antialiased text-gray-900 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navbar -->
        <!-- Top Navbar -->
        <header class="bg-white flex items-center justify-between px-8 shrink-0 shadow-sm border-b border-gray-100 relative z-50" style="height: 60px;">
            <!-- Left Side: Page Title -->
            <div class="flex items-center">
                <button id="sidebarToggleBtn" class="mr-5 text-gray-400 hover:text-[#f0b44b] transition-colors focus:outline-none" style="margin-right: 20px;">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-[20px] font-black text-[#1c2238] tracking-tight">@yield('header', 'Dashboard')</h1>
            </div>
            
            <!-- Right Actions -->
            <div class="flex items-center gap-6">
                
                <!-- Pill Search Bar -->
                <form action="{{ route('passengers.index') }}" method="GET" class="relative hidden sm:block w-56 transition-all duration-300 focus-within:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 text-[13px]"></i>
                    </div>
                    <input type="text" name="search" placeholder="Search passengers, buses..." class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-full bg-gray-50 text-[13px] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#f0b44b] focus:border-[#f0b44b] focus:bg-white shadow-sm transition-all">
                </form>

                <!-- Date -->
                <div class="hidden lg:flex items-center text-[13px] font-bold text-gray-500 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                    <i class="fa-regular fa-calendar text-[#f0b44b] mr-2 text-[14px]"></i>
                    {{ now()->format('d M, Y') }}
                </div>

                <!-- Notifications -->
                <button class="text-gray-400 hover:text-[#f0b44b] transition-colors relative">
                    <i class="fa-regular fa-bell text-[18px]"></i>
                    <span class="absolute top-0 right-0 -mt-0.5 -mr-0.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#f0b44b] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#f0b44b] border border-white"></span>
                    </span>
                </button>

                <!-- User Profile Dropdown -->
                @auth
                <div class="relative flex items-center gap-3 cursor-pointer group pl-2 border-l border-gray-200">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="User Avatar" class="w-9 h-9 rounded-full shadow-sm ring-2 ring-transparent group-hover:ring-[#f0b44b] transition-all object-cover" style="width: 36px; height: 36px; min-width: 36px;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=f0b44b&color=1c2238&bold=true&rounded=true" alt="User Avatar" class="w-9 h-9 rounded-full shadow-sm ring-2 ring-transparent group-hover:ring-[#f0b44b] transition-all">
                    @endif
                    
                    <div class="hidden md:flex flex-col">
                        <span class="text-[13px] font-bold text-[#1c2238] leading-tight">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <span class="text-[11px] font-semibold text-gray-400 leading-tight">System Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 hidden md:block transition-transform group-hover:rotate-180"></i>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 top-full pt-3 w-48 hidden group-hover:block z-[9999]">
                        <div class="bg-white rounded-none shadow-xl py-1 border border-gray-100">
                            <a href="{{ route('settings.index') }}" class="block w-full text-left px-4 py-2.5 text-[13px] font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#f0b44b] transition-colors border-b border-gray-100">
                                <i class="fa-solid fa-gear mr-2"></i> Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2.5 text-[13px] font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('main-sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            // Check local storage for preference
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed-sidebar');
            }

            // Toggle sidebar
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed-sidebar');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed-sidebar'));
            });

            // Initialize TomSelect for all select elements
            document.querySelectorAll('select').forEach((el) => {
                new TomSelect(el, {
                    create: false
                });
            });
        });
    </script>
</body>
</html>

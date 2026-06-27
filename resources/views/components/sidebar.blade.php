<!-- Sidebar Container -->
<aside class="w-64 bg-[#1c2238] text-[#8e98ac] flex flex-col h-full shrink-0 relative transition-all duration-300 z-20 md:flex hidden">
    <!-- Logo/Brand -->
    <div class="h-24 flex items-center px-6 shrink-0 mt-2">
        <div class="w-9 h-9 bg-[#f0b44b] rounded-none flex items-center justify-center text-[#1c2238] mr-3 shadow-lg">
            <i class="fa-solid fa-bus-simple text-[18px]"></i>
        </div>
        <span class="text-[22px] font-bold text-white tracking-wide">Setu</span>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-1">
        
        <!-- Dashboard -->
        <a href="/" class="flex items-center px-4 py-3 mb-1 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->is('/') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-chart-pie w-6 text-center mr-3 text-[16px] {{ request()->is('/') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Dashboard
        </a>

        <!-- Accounting -->
        <a href="{{ route('accounting.index') }}" class="flex items-center px-4 py-3 mb-1 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->routeIs('accounting.index') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-file-invoice-dollar w-6 text-center mr-3 text-[16px] {{ request()->routeIs('accounting.index') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Accounting
        </a>

        <!-- Passengers -->
        <a href="{{ route('passengers.index') }}" class="flex items-center px-4 py-3 mb-1 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->is('passengers*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-users-line w-6 text-center mr-3 text-[16px] {{ request()->is('passengers*') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Passengers
        </a>

        <!-- User -->
        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 mb-1 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->is('users*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-user-shield w-6 text-center mr-3 text-[16px] {{ request()->is('users*') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Users
        </a>

        <!-- Bus -->
        <a href="{{ route('buses.index') }}" class="flex items-center px-4 py-3 mb-1 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->is('buses*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-van-shuttle w-6 text-center mr-3 text-[16px] {{ request()->is('buses*') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Buses
        </a>
        <!-- Print Ticket -->
        <a href="{{ route('tickets.index') }}" class="flex items-center px-4 py-3 mb-6 text-[13px] font-semibold rounded-none transition-all duration-200 {{ request()->routeIs('tickets.*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-[#f0b44b]' }}">
            <i class="fa-solid fa-print w-6 text-center mr-3 text-[16px] {{ request()->routeIs('tickets.*') ? 'text-[#1c2238]' : 'opacity-70' }}"></i>
            Print Ticket
        </a>

        <!-- Logout -->
        <div class="pt-2 border-t border-[#29324b] mt-4">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-[13px] font-semibold rounded-none hover:bg-white/5 hover:text-red-400 border-l-4 border-transparent hover:border-red-400 transition-all text-[#8e98ac]">
                    <i class="fa-solid fa-arrow-right-from-bracket w-6 text-center mr-3 text-[16px]"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- User Profile Footer -->
    <div class="p-6 pb-8 flex items-center shrink-0 border-t border-[#29324b] bg-[#161a2b]">
        <div class="w-10 h-10 rounded-none bg-[#f0b44b] flex items-center justify-center text-[#1c2238] font-bold text-lg mr-4 shrink-0 shadow-md">
            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-white">Het Patel</span>
            <span class="text-[13px] text-[#8e98ac] mt-0.5">Admin</span>
        </div>
    </div>
</aside>

<!-- Sidebar Container -->
<aside id="main-sidebar" class="w-64 bg-[#1c2238] text-[#8e98ac] flex flex-col h-full shrink-0 relative transition-all duration-300 z-20 md:flex hidden">
    <!-- Logo/Brand -->
    <div class="h-24 flex items-center px-6 shrink-0 mt-2 logo-container transition-all">
        @if(file_exists(public_path('images/site-logo.png')))
            <div class="bg-white flex items-center justify-center mx-auto overflow-hidden" style="width: 96%; max-width: 280px; height: 75px; border: 4px solid #f0b44b; border-radius: 100px; padding: 4px;">
                <img src="{{ asset('images/site-logo.png') }}?v={{ filemtime(public_path('images/site-logo.png')) }}" alt="Site Logo" class="object-contain w-full h-full">
            </div>
        @else
            <div class="w-10 h-10 bg-[#f0b44b] rounded-none flex items-center justify-center mr-3 relative overflow-hidden">
                <i class="fa-solid fa-ticket absolute text-[34px] -rotate-12 text-[#1c2238] opacity-30 mt-3 ml-3"></i>
                <i class="fa-solid fa-ticket absolute text-[34px] text-[#1c2238]"></i>
                <i class="fa-solid fa-bus absolute text-[12px] text-[#f0b44b] -ml-2.5 mt-0.5"></i>
                <div class="absolute right-1.5 top-2.5 flex flex-col gap-[2px]">
                    <div class="w-1 h-4 bg-[#f0b44b]"></div>
                    <div class="w-1 h-1 bg-[#f0b44b]"></div>
                </div>
                <div class="absolute right-3.5 top-2.5 flex flex-col gap-[2px]">
                    <div class="w-0.5 h-4 bg-[#f0b44b]"></div>
                    <div class="w-0.5 h-1 bg-[#f0b44b]"></div>
                </div>
            </div>
            <span class="text-[22px] font-bold text-white tracking-wide sidebar-text">Setu</span>
        @endif
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
        
        <div class="px-4 pb-2 pt-1 text-[11px] font-bold text-[#8e98ac] uppercase tracking-[0.2em] opacity-70 sidebar-text">Overview</div>
        
        <!-- Dashboard -->
        <a href="/" class="flex items-center px-4 py-3 mb-1 text-sm font-semibold rounded-none transition-all duration-200 {{ request()->is('/') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Quick Booking -->
        <a href="{{ route('passengers.create') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-semibold rounded-none transition-all duration-200 {{ request()->routeIs('passengers.create') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="sidebar-text">Booking</span>
        </a>

        <!-- Print Ticket -->
        <a href="{{ route('tickets.index') }}" class="flex items-center px-4 py-3 mb-4 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('tickets.*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span class="sidebar-text">Print Ticket</span>
        </a>

        <div class="px-4 pb-2 pt-4 text-[11px] font-bold text-[#8e98ac] uppercase tracking-[0.2em] opacity-70 sidebar-text border-t border-[#29324b] mt-2">Business</div>

        <!-- Accounting -->
        <a href="{{ route('accounting.index') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('accounting.index') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span class="sidebar-text">Accounting</span>
        </a>

        <!-- Settings (Actually Passengers) -->
        <a href="{{ route('passengers.index') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('passengers.index') || request()->routeIs('passengers.edit') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="sidebar-text">Passengers</span>
        </a>

        <!-- Broadcast -->
        <a href="{{ route('broadcast.index') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('broadcast.*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <span class="sidebar-text">Broadcast</span>
        </a>

        <!-- Bus -->
        <a href="{{ route('buses.index') }}" class="flex items-center px-4 py-3 mb-4 text-sm font-medium rounded-none transition-all duration-200 {{ request()->is('buses*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="currentColor" viewBox="0 0 24 24">
                <path opacity="0.6" d="M8 5.5l9.5-2.5c.8-.2 1.6.3 1.8 1.1l2.2 8.3c.2.8-.3 1.6-1.1 1.8l-1.4.4V13c0-2.2-1.8-4-4-4H8V5.5z"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M20 11H4c-1.1 0-2 .9-2 2v1.5c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5V19c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-1.5c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5V13c0-1.1-.9-2-2-2zM17 18h-1v-5h1v5zm-2 0h-2v-5h2v5zm-7.5-4.5h5c.8 0 1.5.7 1.5 1.5v3h-1v1h-1.5v-1h-3v1h-1.5v-1h-1v-3c0-.8.7-1.5 1.5-1.5zm0 1v2h2v-2h-2zm3 0v2h2v-2h-2z"/>
            </svg>
            <span class="sidebar-text">Buses</span>
        </a>
     
        <div class="px-4 pb-2 pt-4 text-[11px] font-bold text-[#8e98ac] uppercase tracking-[0.2em] opacity-70 sidebar-text border-t border-[#29324b] mt-2">Administration</div>
        <!-- User -->
        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-medium rounded-none transition-all duration-200 {{ request()->is('users*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="sidebar-text">Users</span>
        </a>
        <!-- Forms Dropdown -->
        <div class="mb-1" id="forms-dropdown">
            <button onclick="document.getElementById('forms-menu').classList.toggle('hidden'); document.getElementById('forms-chevron').classList.toggle('rotate-180');" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-none transition-all duration-200 {{ (request()->is('forms*') || request()->routeIs('contracts.*')) ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
                <div class="flex items-center">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
                    <span class="sidebar-text">Forms</span>
                </div>
                <i id="forms-chevron" class="fa-solid fa-chevron-down text-[10px] transition-transform sidebar-text {{ (request()->is('forms*') || request()->routeIs('contracts.*')) ? 'rotate-180' : '' }}"></i>
            </button>
            
            <div id="forms-menu" class="pl-12 pr-4 py-2 space-y-1 bg-black/20 sidebar-text {{ (request()->is('forms*') || request()->routeIs('contracts.*')) ? '' : 'hidden' }}">
                <a href="{{ route('forms.index') }}" class="block px-4 py-2 text-[13px] font-medium rounded transition-all {{ request()->is('forms*') ? 'text-[#f0b44b] font-bold' : 'text-[#8e98ac] hover:text-white hover:translate-x-1' }}">
                    <i class="fa-solid fa-file-lines w-4 mr-1 text-center"></i> Builder Forms
                </a>
                <a href="{{ route('contracts.index') }}" class="block px-4 py-2 text-[13px] font-medium rounded transition-all {{ request()->routeIs('contracts.*') ? 'text-[#f0b44b] font-bold' : 'text-[#8e98ac] hover:text-white hover:translate-x-1' }}">
                    <i class="fa-solid fa-file-contract w-4 mr-1 text-center"></i> Contracts
                </a>
            </div>
        </div>

        <!-- Personal Accounts Dropdown -->
        <div class="mb-1" id="personal-accounts-dropdown">
            <button onclick="document.getElementById('personal-accounts-menu').classList.toggle('hidden'); document.getElementById('pa-chevron').classList.toggle('rotate-180');" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-none transition-all duration-200 {{ (request()->routeIs('personal-accounts.*') || request()->routeIs('agent-tickets.*')) ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="sidebar-text">Personal Account</span>
                </div>
                <i id="pa-chevron" class="fa-solid fa-chevron-down text-[10px] transition-transform sidebar-text {{ (request()->routeIs('personal-accounts.*') || request()->routeIs('agent-tickets.*')) ? 'rotate-180' : '' }}"></i>
            </button>
            
            <div id="personal-accounts-menu" class="pl-12 pr-4 py-2 space-y-1 bg-black/20 sidebar-text {{ (request()->routeIs('personal-accounts.*') || request()->routeIs('agent-tickets.*')) ? '' : 'hidden' }}">
                <a href="{{ route('personal-accounts.index') }}" class="block px-4 py-2 text-[13px] font-medium rounded transition-all {{ request()->routeIs('personal-accounts.*') && !request()->routeIs('agent-tickets.*') ? 'text-[#f0b44b] font-bold' : 'text-[#8e98ac] hover:text-white hover:translate-x-1' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-4 mr-1 text-center"></i> Expenses
                </a>
                <a href="{{ route('agent-tickets.index') }}" class="block px-4 py-2 text-[13px] font-medium rounded transition-all {{ request()->routeIs('agent-tickets.*') ? 'text-[#f0b44b] font-bold' : 'text-[#8e98ac] hover:text-white hover:translate-x-1' }}">
                    <i class="fa-solid fa-user-tie w-4 mr-1 text-center"></i> Agent Sales
                </a>
            </div>
        </div>

        <!-- Personal Accounts -->
        <a href="{{ route('personal-accounts.index') }}" class="flex items-center px-4 py-3 mb-1 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('personal-accounts.*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="sidebar-text">Personal Accounts</span>
        </a>

        <!-- Settings -->
        <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 mb-6 text-sm font-medium rounded-none transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-[#f0b44b] text-[#1c2238] shadow-md shadow-[#f0b44b]/20' : 'border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5' }}">
            <svg class="w-5 h-5 mr-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="sidebar-text">Settings</span>
        </a>

        <!-- Logout -->
        <div class="pt-4 mt-2">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center px-4 py-3 text-sm font-medium rounded-none transition-all duration-200 border-l-[3px] border-transparent hover:border-[#f0b44b] hover:bg-white/5 hover:text-white hover:pl-5 w-full text-left">
                    <svg class="w-5 h-5 mr-4 shrink-0 opacity-80 text-[#f0b44b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="sidebar-text text-[#f0b44b]">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- User Profile Footer -->
    <div class="p-6 pb-8 flex items-center shrink-0 border-t border-[#29324b] mt-4 user-footer transition-all">
        @auth
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover mr-4 shrink-0 border-2 border-[#f0b44b] user-avatar transition-all" style="width: 40px; height: 40px; min-width: 40px;">
            @else
                <div class="w-10 h-10 rounded-full bg-[#f0b44b] flex items-center justify-center text-[#1c2238] font-bold text-lg mr-4 shrink-0 user-avatar transition-all">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
            @endif
            <div class="flex flex-col sidebar-text transition-all">
                <span class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                <span class="text-[13px] text-[#8e98ac] mt-0.5">Admin</span>
            </div>
        @endauth
    </div>
</aside>

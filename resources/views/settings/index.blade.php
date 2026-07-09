@extends('layouts.app')

@section('title', 'Settings')
@section('header', 'Settings')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-[#e8f5ed] border-l-4 border-[#34a853] text-[#34a853] rounded-none text-[13px] font-semibold shadow-sm flex items-center">
            <i class="fa-solid fa-circle-check mr-3 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-[#fee2e2] border-l-4 border-[#ef4444] text-[#ef4444] rounded-none text-[13px] font-semibold shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc pl-9">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Profile Settings Card -->
        <div class="bg-white rounded-none shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#f0b44b]/20 flex items-center justify-center text-[#f0b44b]">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h2 class="text-[16px] font-bold text-[#1c2238]">Profile Information</h2>
            </div>
            <div class="p-6">
                <!-- Avatar Upload -->
                <div class="flex items-center gap-6 mb-8 bg-gray-50 p-4 border border-gray-100">
                    <div class="relative shrink-0 overflow-hidden shadow-sm border-2 border-white bg-white" style="width: 80px; height: 80px; min-width: 80px; max-width: 80px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="User Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=f0b44b&color=1c2238&bold=true&rounded=false" alt="User Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[13px] font-bold text-[#1c2238] mb-1">Profile Photo</h4>
                        <p class="text-[11px] text-gray-500 mb-3">Upload a new photo to update your avatar.</p>
                        <label class="cursor-pointer inline-flex items-center px-4 py-1.5 bg-[#1c2238] hover:bg-[#29324b] text-white text-[11px] font-bold uppercase tracking-wider rounded-none transition-colors shadow-sm">
                            <i class="fa-solid fa-upload mr-2"></i> Select Image
                            <input type="file" name="avatar" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-id-badge text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Agency WhatsApp Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-brands fa-whatsapp text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" placeholder="e.g. +919876543210">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">This number can be used for official communication or printed on tickets.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Card -->
        <div class="bg-white rounded-none shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="text-[16px] font-bold text-[#1c2238]">Security Settings</h2>
            </div>
            <div class="p-6">
                <p class="text-[12px] text-gray-500 mb-6">Leave the password fields blank if you do not wish to change your current password.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="password" name="password" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 text-[13px] bg-white transition-colors" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="password" name="password_confirmation" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 text-[13px] bg-white transition-colors" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp API Card -->
        <div class="bg-white rounded-none shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#25d366]/10 flex items-center justify-center text-[#25d366]">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h2 class="text-[16px] font-bold text-[#1c2238]">WhatsApp API Configuration (Meta)</h2>
            </div>
            <div class="p-6">
                <p class="text-[12px] text-gray-500 mb-6">Configure your Meta Developer credentials to enable automatic WhatsApp message broadcasting.</p>
                <div class="grid grid-cols-1 gap-6 mb-4">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Access Token</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-key text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="text" name="whatsapp_access_token" value="{{ old('whatsapp_access_token', $user->whatsapp_access_token) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" placeholder="EAAD...">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number ID</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-hashtag text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="text" name="whatsapp_phone_number_id" value="{{ old('whatsapp_phone_number_id', $user->whatsapp_phone_number_id) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" placeholder="1234567890">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Business Account ID</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-briefcase text-gray-400 text-[13px]"></i>
                            </div>
                            <input type="text" name="whatsapp_business_account_id" value="{{ old('whatsapp_business_account_id', $user->whatsapp_business_account_id) }}" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors" placeholder="0987654321">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Site Appearance Card -->
        <div class="bg-white rounded-none shadow-sm mb-8">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <h2 class="text-[16px] font-bold text-[#1c2238]">Site Appearance</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Site Logo -->
                    <div class="group border border-gray-200 relative p-1">
                        <div class="bg-gray-50 p-5 h-full flex flex-col items-center text-center justify-center border border-dashed border-gray-300 group-hover:bg-[#fcf8f2] group-hover:border-[#f0b44b] transition-colors relative">
                            <div class="mb-3 min-h-[48px] flex items-center justify-center">
                                @if(file_exists(public_path('images/site-logo.png')))
                                    <img src="{{ asset('images/site-logo.png') }}?v={{ time() }}" alt="Logo" class="max-h-16 max-w-full object-contain">
                                @else
                                    <i class="fa-solid fa-image text-3xl text-gray-300 group-hover:text-[#f0b44b]"></i>
                                @endif
                            </div>
                            <h4 class="text-[13px] font-bold text-gray-700">Site Logo</h4>
                            <p class="text-[11px] text-gray-500 mt-1 mb-3">Replaces the sidebar icon.</p>
                            <label class="cursor-pointer px-4 py-1.5 bg-white border border-gray-300 text-gray-600 text-[11px] font-bold rounded-none shadow-sm hover:border-[#f0b44b] hover:text-[#f0b44b] transition-colors">
                                Browse File
                                <input type="file" name="site_logo" accept="image/*" class="hidden file-input">
                            </label>
                            <span class="file-name text-[10px] text-[#f0b44b] font-bold mt-2 hidden truncate max-w-full px-2"></span>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="group border border-gray-200 relative p-1">
                        <div class="bg-gray-50 p-5 h-full flex flex-col items-center text-center justify-center border border-dashed border-gray-300 group-hover:bg-[#fcf8f2] group-hover:border-[#f0b44b] transition-colors relative">
                            <div class="mb-3">
                                @if(file_exists(public_path('favicon.ico')))
                                    <div class="w-12 h-12 mx-auto bg-white border border-gray-200 flex items-center justify-center p-1.5 shadow-sm">
                                        <img src="{{ asset('favicon.ico') }}?v={{ time() }}" alt="Favicon" class="w-full h-full object-contain">
                                    </div>
                                @else
                                    <i class="fa-solid fa-window-restore text-3xl text-gray-300 group-hover:text-[#f0b44b]"></i>
                                @endif
                            </div>
                            <h4 class="text-[13px] font-bold text-gray-700">Site Favicon</h4>
                            <p class="text-[11px] text-gray-500 mt-1 mb-3">Appears in the browser tab.</p>
                            <label class="cursor-pointer px-4 py-1.5 bg-white border border-gray-300 text-gray-600 text-[11px] font-bold rounded-none shadow-sm hover:border-[#f0b44b] hover:text-[#f0b44b] transition-colors">
                                Browse File
                                <input type="file" name="site_favicon" accept=".ico,.png,.jpg,.jpeg" class="hidden file-input">
                            </label>
                            <span class="file-name text-[10px] text-[#f0b44b] font-bold mt-2 hidden truncate max-w-full px-2"></span>
                        </div>
                    </div>

                    <!-- Login Image -->
                    <div class="group border border-gray-200 relative p-1">
                        <div class="bg-gray-50 p-5 h-full flex flex-col items-center text-center justify-center border border-dashed border-gray-300 group-hover:bg-[#fcf8f2] group-hover:border-[#f0b44b] transition-colors relative">
                            <div class="mb-3">
                                @if(file_exists(public_path('images/login-image.png')))
                                    <div class="h-12 w-full mx-auto bg-white border border-gray-200 flex items-center justify-center p-1.5 shadow-sm">
                                        <img src="{{ asset('images/login-image.png') }}?v={{ time() }}" alt="Login Image" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <i class="fa-solid fa-image text-3xl text-gray-300 group-hover:text-[#f0b44b]"></i>
                                @endif
                            </div>
                            <h4 class="text-[13px] font-bold text-gray-700">Login Page Image</h4>
                            <p class="text-[11px] text-gray-500 mt-1 mb-3">Split image for login screen.</p>
                            <label class="cursor-pointer px-4 py-1.5 bg-white border border-gray-300 text-gray-600 text-[11px] font-bold rounded-none shadow-sm hover:border-[#f0b44b] hover:text-[#f0b44b] transition-colors">
                                Browse File
                                <input type="file" name="login_image" accept="image/*" class="hidden file-input">
                            </label>
                            <span class="file-name text-[10px] text-[#f0b44b] font-bold mt-2 hidden truncate max-w-full px-2"></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-black py-4 text-[14px] uppercase tracking-wider rounded-none hover:bg-[#e0a43b] transition-all shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-floppy-disk mr-2"></i> Save All Settings
        </button>

    </form>
</div>

<script>
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            if(this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const container = this.closest('.group');
                const nameDisplay = container.querySelector('.file-name');
                
                nameDisplay.textContent = fileName;
                nameDisplay.classList.remove('hidden');
                
                // Add border highlight
                container.querySelector('.border-dashed').classList.add('border-[#f0b44b]', 'bg-[#fcf8f2]');
            }
        });
    });
</script>
@endsection

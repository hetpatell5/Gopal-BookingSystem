<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bus Booking System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom styles to override any default input borders for the specific look */
        .bottom-border-input {
            border: none;
            border-bottom: 1px solid #d1d5db;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
            background-color: transparent;
            box-shadow: none !important;
        }
        .bottom-border-input:focus {
            border-bottom-color: #f0b44b;
            outline: none;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="bg-[#f3f4f6] flex items-center justify-center min-h-screen p-4 md:p-8 font-sans">
    
    <!-- Main Card Container -->
    <div class="bg-white rounded-[32px] shadow-lg flex flex-col md:flex-row w-full max-w-5xl p-2 min-h-[600px]">
        
        <!-- Left side - Image -->
        <div class="hidden md:block w-1/2 relative bg-[#eef1f6] rounded-[24px] overflow-hidden">
            @if(file_exists(public_path('images/login-image.png')))
                <img src="{{ asset('images/login-image.png') }}?v={{ time() }}" alt="Login Image" class="absolute inset-0 w-full h-full object-cover">
            @else
                <!-- Default placeholder if no image uploaded -->
                <div class="absolute inset-0 w-full h-full bg-[#1c2238] flex flex-col items-center justify-center text-center p-10">
                    <i class="fa-solid fa-bus text-[80px] text-[#f0b44b] mb-6 opacity-80"></i>
                    <h2 class="text-3xl font-black text-white mb-3">Bus Booking</h2>
                    <p class="text-[#8e98ac] text-sm max-w-[250px]">Manage your bus fleet, track bookings, and optimize routes.</p>
                </div>
            @endif
        </div>

        <!-- Right side - Form -->
        <div class="w-full md:w-1/2 flex flex-col justify-center px-8 md:px-16 py-12 relative">
            <div class="w-full max-w-sm mx-auto">
                
                <div class="text-center mb-10">
                   

                    <h1 class="text-2xl font-black text-[#1c2238] mb-2 tracking-tight">Welcome Back</h1>
                    <p class="text-[13px] text-gray-500 font-semibold">Please sign in to your account</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-3 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] font-medium text-[14px]">
                    </div>

                    <div class="mb-6">
                        <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] font-medium text-[14px]">
                    </div>

                    <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-black py-3.5 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm mt-2 uppercase tracking-wide">
                        Sign In
                    </button>
                </form>
                
            </div>
        </div>
    </div>

</body>
</html>

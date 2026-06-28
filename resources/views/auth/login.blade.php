<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bus Booking System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-[#1c2238] mb-1">Welcome Back</h1>
            <p class="text-sm text-gray-500 font-medium">Please sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-none text-sm font-semibold border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <div class="mb-6">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-3.5 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm">
                Sign In
            </button>
        </form>
    </div>

</body>
</html>

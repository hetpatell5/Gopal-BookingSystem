@extends('layouts.app')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-none shadow-sm p-6 relative">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-[17px] font-bold text-[#1c2238]">Edit {{ $user->name }}</h2>
            <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
            </a>
        </div>
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-[#e8f5ed] text-[#34a853] rounded-none text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-none text-sm font-semibold">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter user name" required>
            </div>

            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter email address" required>
            </div>

            <div class="mb-6">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Password <span class="text-gray-400 font-normal lowercase">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter new password">
            </div>

            <div class="mb-6">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Profile Image (Optional)</label>
                @if($user->avatar)
                    <div class="mb-3 flex items-center">
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 mr-3">
                        <span class="text-xs text-gray-500">Current Avatar</span>
                    </div>
                @endif
                <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-[#f0b44b] file:text-[#1c2238] hover:file:bg-[#e0a43b] transition-colors cursor-pointer">
            </div>

            <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-2.5 rounded-none hover:bg-[#e0a43b] transition-colors">
                Update User
            </button>
        </form>
    </div>
</div>
@endsection

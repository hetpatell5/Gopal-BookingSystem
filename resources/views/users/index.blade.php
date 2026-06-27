@extends('layouts.app')

@section('title', 'Manage Users')
@section('header', 'Manage Users')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Add User Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[10px] shadow-sm p-6">
            <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Add New User</h2>
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-[#e8f5ed] text-[#34a853] rounded-md text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-md text-sm font-semibold">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter user name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter email address" required>
                </div>

                <div class="mb-6">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter password" required>
                </div>

                <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-2.5 rounded-md hover:bg-[#e0a43b] transition-colors">
                    Add User
                </button>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[10px] shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-[17px] font-bold text-[#1c2238]">Existing Users</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Name</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Email</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-[13px] text-gray-500 font-medium">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Manage Users')
@section('header', 'Manage Users')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Add User Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-none shadow-sm p-6">
            <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Add New User</h2>
            
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

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter user name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter email address" required>
                </div>

                <div class="mb-6">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter password" required>
                </div>

                <div class="mb-6">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Profile Image (Optional)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-[#f0b44b] file:text-[#1c2238] hover:file:bg-[#e0a43b] transition-colors cursor-pointer">
                </div>

                <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-2.5 rounded-none hover:bg-[#e0a43b] transition-colors">
                    Add User
                </button>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-none shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-[17px] font-bold text-[#1c2238]">Existing Users</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Name</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Email</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Password</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Joined</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                                <div class="flex items-center">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover mr-3 border border-gray-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-[#f0b44b] flex items-center justify-center text-[#1c2238] font-bold mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="password-display" data-password="{{ $user->raw_password ?: 'Not Saved (Please Edit)' }}">***</span>
                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none toggle-password" title="Show Password">
                                        <i class="fa-solid fa-eye text-[12px]"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit User">
                                        <i class="fa-solid fa-pen text-[16px]"></i>
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Delete User">
                                            <i class="fa-solid fa-trash text-[16px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[13px] text-gray-500 font-medium">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const span = this.previousElementSibling;
                const password = span.getAttribute('data-password');
                
                if (icon.classList.contains('fa-eye')) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    span.textContent = password;
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    span.textContent = '***';
                }
            });
        });
    });
</script>
@endsection

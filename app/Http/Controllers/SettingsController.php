<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:1024',
            'login_image' => 'nullable|image|max:5120',
            'whatsapp_access_token' => 'nullable|string|max:1000',
            'whatsapp_phone_number_id' => 'nullable|string|max:255',
            'whatsapp_business_account_id' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->whatsapp_access_token = $request->whatsapp_access_token;
        $user->whatsapp_phone_number_id = $request->whatsapp_phone_number_id;
        $user->whatsapp_business_account_id = $request->whatsapp_business_account_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->raw_password = $request->password;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if ($request->hasFile('site_logo')) {
            $request->file('site_logo')->move(public_path('images'), 'site-logo.png');
        }

        if ($request->hasFile('site_favicon')) {
            $request->file('site_favicon')->move(public_path(), 'favicon.ico');
        }

        if ($request->hasFile('login_image')) {
            $request->file('login_image')->move(public_path('images'), 'login-image.png');
        }

        $user->save();

        return redirect()->route('settings.index')->with('success', 'Profile updated successfully.');
    }
}

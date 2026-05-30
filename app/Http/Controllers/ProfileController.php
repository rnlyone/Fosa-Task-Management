<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.index', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        // Remove old avatar file from public/avatars/
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }

        // Store directly in public/avatars/ — no storage:link needed
        $dir      = public_path('avatars');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = uniqid('avatar_') . '.' . $request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->move($dir, $filename);

        $user->update(['avatar' => 'avatars/' . $filename]);

        return back()->with('success', 'Avatar updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => ['required', 'in:free,available,busy,very_busy,not_available,cant_be_bothered'],
        ]);

        auth()->user()->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }
}

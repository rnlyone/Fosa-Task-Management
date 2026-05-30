<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SmartMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        // Always save name & username immediately
        $user->update(['name' => $data['name'], 'username' => $data['username']]);

        if ($data['email'] !== $user->email) {
            // Generate a secure one-time token
            $token = Str::random(64);

            $user->update([
                'pending_email'      => $data['email'],
                'email_change_token' => hash('sha256', $token),
            ]);

            $link    = route('profile.verify-email', ['token' => $token]);
            $html    = view('emails.verify-email-change', [
                'user'     => $user,
                'link'     => $link,
                'newEmail' => $data['email'],
            ])->render();

            app(SmartMailerService::class)->send(
                $data['email'],
                $user->name,
                'Verify Your New Email Address — FOSA Task Management',
                $html
            );

            return back()->with('info', "Profile saved. A verification link has been sent to {$data['email']}. Your email address will only be updated after you click that link.");
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function verifyEmailChange(string $token)
    {
        $hashed = hash('sha256', $token);
        $user   = User::where('email_change_token', $hashed)->whereNotNull('pending_email')->first();

        if (! $user) {
            $msg = 'This verification link is invalid or has already been used.';
            return Auth::check()
                ? redirect()->route('profile.show')->with('error', $msg)
                : redirect()->route('login')->with('error', $msg);
        }

        $user->update([
            'email'              => $user->pending_email,
            'pending_email'      => null,
            'email_change_token' => null,
        ]);

        return Auth::check()
            ? redirect()->route('profile.show')->with('success', 'Your email address has been updated successfully.')
            : redirect()->route('login')->with('success', 'Email verified. Please log in with your new address.');
    }

    public function cancelEmailChange(Request $request)
    {
        $request->user()->update([
            'pending_email'      => null,
            'email_change_token' => null,
        ]);

        return back()->with('success', 'Pending email change has been cancelled.');
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AvatarController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'avatar'          => 'nullable|string',
            'avatar_initials' => 'nullable|string|max:4',
        ]);

        $user  = Auth::user();
        $panel = $user->panel ?? 'admin';

        $user->update([
            'avatar'          => $request->input('avatar') ?: null,
            'avatar_initials' => $request->input('avatar_initials')
                ? strtoupper(substr($request->input('avatar_initials'), 0, 2))
                : null,
        ]);

        // Redirect back to exactly where the user was
        $fallback = match($panel) {
            'doctor'  => '/doctor/patient-queues',
            'patient' => '/patient/my-records',
            default   => '/' . $panel,
        };

        return redirect($request->headers->get('referer') ?: $fallback)
            ->with('avatar_success', 'Profile updated successfully.');
    }

    public function savePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'confirm_password.same' => 'Passwords do not match.',
            'new_password.min'      => 'New password must be at least 8 characters.',
        ]);

        $user  = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput()
                ->with('open_security_tab', true);
        }

        if ($request->current_password === $request->new_password) {
            return back()
                ->withErrors(['new_password' => 'New password must differ from your current password.'])
                ->withInput()
                ->with('open_security_tab', true);
        }

        $user->update([
            'password'              => Hash::make($request->new_password),
            'force_password_change' => false,
        ]);

        // Re-sync the session's stored password hash so AuthenticateSession
        // middleware doesn't log the user out after the password change.
        $request->session()->put(
            'password_hash_web',
            $user->fresh()->password
        );

        return back()->with('avatar_success', 'Password changed successfully.');
    }
}
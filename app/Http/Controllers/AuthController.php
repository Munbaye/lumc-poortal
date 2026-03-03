<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private array $panelRoutes = [
        'admin'   => '/admin',
        'doctor'  => '/doctor/patient-queues',
        'nurse'   => '/nurse',
        'clerk'   => '/clerk',
        'tech'    => '/tech',
        'patient' => '/patient',
    ];

    // ─── PATIENT LOGIN ────────────────────────────────────────────────────────
    // Accepts username (JuanDelaCruz25) — no @ needed
    public function patientLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->email); // field is named 'email' in form but accepts username
        $password   = $request->password;

        // Search by username field first, then name, then email
        $user = User::where('username', $loginInput)
                    ->orWhere('name', $loginInput)
                    ->orWhere('email', $loginInput)
                    ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        if ($user->panel !== 'patient') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'This login is for patients only.');
        }

        if (!$user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account is inactive. Please contact the hospital.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // If forced password change, redirect back to homepage with modal open in change-password mode
        if ($user->force_password_change) {
            return redirect('/?change_password=1');
        }

        return redirect('/patient/my-records');
    }

    // ─── PATIENT CHANGE PASSWORD ──────────────────────────────────────────────
    public function patientChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'confirm_password.same' => 'Passwords do not match.',
            'new_password.min'      => 'Password must be at least 8 characters.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('change_error', 'The current password is incorrect.')->with('change_password_open', true);
        }

        if ($request->current_password === $request->new_password) {
            return back()->with('change_error', 'New password must differ from current password.')->with('change_password_open', true);
        }

        $user->update([
            'password'              => Hash::make($request->new_password),
            'force_password_change' => false,
        ]);

        return redirect('/patient/my-records');
    }

    // ─── STAFF LOGIN ──────────────────────────────────────────────────────────
    public function staffLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->panel === 'patient') {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Patients must log in from the main website, not the Staff Portal.');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Your account has been deactivated. Contact the administrator.');
            }

            $request->session()->regenerate();
            return redirect($this->panelRoutes[$user->panel] ?? '/admin');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password. Please try again.');
    }

    // ─── LOGOUT ───────────────────────────────────────────────────────────────
    // Checks who is logging out BEFORE destroying the session.
    // Staff  → /staff  (our custom staff login page)
    // Patient → /      (landing page with patient login modal)
    // Unknown → /      (safe fallback)
    public function logout(Request $request)
    {
        $panel = Auth::check() ? Auth::user()->panel : null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $staffPanels = ['admin', 'doctor', 'nurse', 'clerk', 'tech'];
        return redirect(in_array($panel, $staffPanels) ? '/staff' : '/');
    }
}
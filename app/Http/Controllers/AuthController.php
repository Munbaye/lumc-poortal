<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function patientLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->panel !== 'patient') {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'This login is for patients only. Staff should use the Staff Portal.');
            }

            if (! $user->is_active) {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Your account is inactive. Please contact the hospital.');
            }

            $request->session()->regenerate();
            return redirect('/patient');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
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

            if (! $user->is_active) {
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
        // Read panel BEFORE we destroy the session
        $panel = Auth::check() ? Auth::user()->panel : null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Staff go back to the staff login page; everyone else to the landing page
        $staffPanels = ['admin', 'doctor', 'nurse', 'clerk', 'tech'];

        return redirect(in_array($panel, $staffPanels) ? '/staff' : '/');
    }
}
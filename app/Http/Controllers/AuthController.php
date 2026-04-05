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

    // ─── PATIENT LOGIN ─────────────────────────────────────────────────────────
    public function patientLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->email);
        $password   = $request->password;

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

        if ($user->force_password_change) {
            return redirect('/?change_password=1');
        }

        return redirect('/patient/my-records');
    }

    // ─── PATIENT CHANGE PASSWORD ───────────────────────────────────────────────
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

    // ─── STAFF LOGIN ───────────────────────────────────────────────────────────
    // Accepts email OR username — whichever the staff member types
    public function staffLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->email);

        // Try to find by email first, then by username
        $user = User::where('email', $loginInput)
                    ->orWhere('username', $loginInput)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid credentials. Please try again.');
        }

        if ($user->panel === 'patient') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Patients must log in from the main website, not the Staff Portal.');
        }

        if (!$user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account has been deactivated. Contact the administrator.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Force password change: redirect back to /staff with modal flag
        if ($user->force_password_change) {
            return redirect('/staff?change_password=1');
        }

        return redirect($this->panelRoutes[$user->panel] ?? '/admin');
    }

    // ─── STAFF CHANGE PASSWORD ─────────────────────────────────────────────────
    public function staffChangePassword(Request $request)
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
            return redirect('/staff?change_password=1')
                ->with('staff_change_error', 'The current password is incorrect.');
        }

        if ($request->current_password === $request->new_password) {
            return redirect('/staff?change_password=1')
                ->with('staff_change_error', 'New password must differ from your current password.');
        }

        $panel = $user->panel;

        $user->update([
            'password'              => Hash::make($request->new_password),
            'force_password_change' => false,
        ]);

        Auth::login($user);

        return redirect($this->panelRoutes[$panel] ?? '/admin');
    }

    // ─── LOGOUT ────────────────────────────────────────────────────────────────
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
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\ChartController;

// ── PUBLIC LANDING PAGE ────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        // Staff panels: redirect them away from the public homepage
        $staffRoutes = [
            'admin'  => '/admin',
            'doctor' => '/doctor/patient-queues',
            'nurse'  => '/nurse',
            'clerk'  => '/clerk',
            'tech'   => '/tech',
        ];

        if (isset($staffRoutes[$user->panel])) {
            return redirect($staffRoutes[$user->panel]);
        }

        // Patients with force_password_change: stay on homepage so the modal can open
        if ($user->panel === 'patient' && $user->force_password_change) {
            return view('welcome');
        }

        // Patients fully logged in: send to their records
        if ($user->panel === 'patient') {
            return redirect('/patient/my-records');
        }
    }

    return view('welcome');
});

// ── PATIENT LOGIN ──────────────────────────────────────────────────────────────
Route::post('/patient-login', [AuthController::class, 'patientLogin'])
    ->name('patient.login.submit');

// ── PATIENT CHANGE PASSWORD ────────────────────────────────────────────────────
Route::post('/patient-change-password', [AuthController::class, 'patientChangePassword'])
    ->name('patient.change.password')
    ->middleware('auth');

// ── STAFF PORTAL ───────────────────────────────────────────────────────────────
Route::get('/staff', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->panel === 'patient') return redirect('/patient');
        $routes = [
            'admin'  => '/admin',
            'doctor' => '/doctor/patient-queues',
            'nurse'  => '/nurse',
            'clerk'  => '/clerk',
            'tech'   => '/tech',
        ];
        return redirect($routes[$user->panel] ?? '/admin');
    }
    return view('staff-login');
})->name('staff.login');

Route::post('/staff/login', [AuthController::class, 'staffLogin'])
    ->name('staff.login.submit');

// ── MASTER LOGOUT ──────────────────────────────────────────────────────────────
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Intercept every Filament panel logout route.
foreach (['admin', 'doctor', 'nurse', 'clerk', 'tech', 'patient'] as $panel) {
    Route::match(['get', 'post'], "/{$panel}/logout", [AuthController::class, 'logout'])
        ->name("{$panel}.logout");
}

// ── NAMED LOGIN FALLBACK ───────────────────────────────────────────────────────
// Laravel's AuthenticateSession and other internals look for route('login').
// We have no separate login page — the login modal lives on the homepage.
Route::get('/login', function () {
    return redirect('/');
})->name('login');

// ── PASSWORD RESET PLACEHOLDER ─────────────────────────────────────────────────
Route::get('/forgot-password', function () {
    return redirect('/staff')->with('error', 'Password reset is not yet available. Please contact the administrator.');
})->name('password.request');

Route::get('/forms/consent-to-care/{visit}', [ConsentController::class, 'consentToCare'])
    ->middleware(['auth'])
    ->name('forms.consent-to-care');

Route::get('/forms/history-form/{visit}', [ChartController::class, 'historyForm'])
    ->name('forms.history-form');

// NUR-005 — Physical Examination Form (printable document, new tab)
Route::get('/forms/physical-exam-form/{visit}', [ChartController::class, 'physicalExamForm'])
    ->name('forms.physical-exam-form');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ── PUBLIC LANDING PAGE ────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $routes = [
            'admin'   => '/admin',
            'doctor'  => '/doctor/patient-queues',
            'nurse'   => '/nurse',
            'clerk'   => '/clerk',
            'tech'    => '/tech',
            'patient' => '/patient',
        ];
        return redirect($routes[auth()->user()->panel] ?? '/admin');
    }
    return view('welcome');
});

// ── PATIENT LOGIN ──────────────────────────────────────────────────────────────
Route::post('/patient-login', [AuthController::class, 'patientLogin'])
    ->name('patient.login.submit');

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

// ── PASSWORD RESET PLACEHOLDER ─────────────────────────────────────────────────
Route::get('/forgot-password', function () {
    return redirect('/staff')->with('error', 'Password reset is not yet available. Please contact the administrator.');
})->name('password.request');
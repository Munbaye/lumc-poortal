<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ClerkFormController;
use App\Http\Controllers\NurseFormController;

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

        // If staff has force_password_change, keep them on /staff so modal can open
        if ($user->panel !== 'patient' && $user->force_password_change) {
            return view('staff-login');
        }

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

// ── STAFF CHANGE PASSWORD ──────────────────────────────────────────────────────
Route::post('/staff/change-password', [AuthController::class, 'staffChangePassword'])
    ->name('staff.change.password')
    ->middleware('auth');

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

Route::middleware(['auth'])->group(function () {

    // ── ER Record ─────────────────────────────────────────────────────────────
    Route::get('/forms/er-record/{visit}', [ClerkFormController::class, 'erRecord'])
        ->name('forms.er-record');
    Route::post('/forms/er-record/{visit}/save', [ClerkFormController::class, 'erRecordSave'])
        ->name('forms.er-record.save');

    // ── Admission Record ──────────────────────────────────────────────────────
    Route::get('/forms/adm-record/{visit}', [ClerkFormController::class, 'admRecord'])
        ->name('forms.adm-record');
    Route::post('/forms/adm-record/{visit}/save', [ClerkFormController::class, 'admRecordSave'])
        ->name('forms.adm-record.save');

    // ── Consent to Care ───────────────────────────────────────────────────────
    Route::get('/forms/consent-to-care/{visit}',       [ConsentController::class, 'consentToCare'])->name('forms.consent-to-care');
    Route::post('/forms/consent-to-care/{visit}/save', [ConsentController::class, 'consentSave'])  ->name('forms.consent-to-care.save');

    // ── Clinical Document Forms ───────────────────────────────────────────────
    Route::get('/forms/history-form/{visit}',       [ChartController::class, 'historyForm'])    ->name('forms.history-form');
    Route::get('/forms/physical-exam-form/{visit}', [ChartController::class, 'physicalExamForm'])->name('forms.physical-exam-form');

    // ── Laboratory Request ────────────────────────────────────────────────────
    Route::get('/forms/lab-request/{visit}',  [ChartController::class, 'labRequest'])     ->name('forms.lab-request');
    Route::post('/forms/lab-request/{visit}', [ChartController::class, 'labRequestStore'])->name('forms.lab-request.store');

    // ── Radiology Request ─────────────────────────────────────────────────────
    Route::get('/forms/radiology-request/{visit}',  [ChartController::class, 'radiologyRequest'])     ->name('forms.radiology-request');
    Route::post('/forms/radiology-request/{visit}', [ChartController::class, 'radiologyRequestStore'])->name('forms.radiology-request.store');

    // ── Tech Result Uploads ───────────────────────────────────────────────────
    Route::post('/results/lab/{labRequest}/upload', [ResultController::class, 'uploadLabResult'])->name('results.lab.upload');
    Route::post('/results/rad/{radRequest}/upload', [ResultController::class, 'uploadRadResult'])->name('results.rad.upload');

    // ── Nursing / Clinical Printable Forms — visit-scoped ─────────────────────
    // These accept a {visit} route parameter so the form can display real data.
    Route::get('/forms/vital-sign-monitoring-sheet/{visit}', [NurseFormController::class, 'vitalSignSheet'])
        ->name('forms.vital-sign-monitoring-sheet');

    Route::get('/forms/iv-bt-sheet/{visit}', [NurseFormController::class, 'ivBtSheet'])
        ->name('forms.iv-bt-sheet');

    Route::get('/forms/nurses-notes/{visit}', [NurseFormController::class, 'nursesNotes'])
        ->name('forms.nurses-notes');
 
    Route::get('/forms/medication-records/{visit}', [NurseFormController::class, 'medicationRecords'])
        ->name('forms.medication-records');

});
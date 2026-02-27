<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!auth()->check()) {
        return view('welcome');
    }
    $panelRoutes = [
        'admin'   => '/admin',
        'doctor'  => '/doctor/patient-queues',
        'nurse'   => '/nurse',
        'clerk'   => '/clerk',
        'tech'    => '/tech',
        'patient' => '/patient',
    ];
    $target = $panelRoutes[auth()->user()->panel ?? 'admin'] ?? '/admin/login';
    return redirect($target);
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

Route::get('/switch/{panel}', function (string $panel) {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    $validPanels = ['admin', 'doctor', 'nurse', 'clerk', 'tech', 'patient'];
    $target = in_array($panel, $validPanels) ? "/{$panel}/login" : '/';
    return redirect($target);
});
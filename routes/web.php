<?php
// routes/web.php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        // Show a central landing/login selector page
        return view('welcome');
    }

    // Redirect logged-in users to their correct panel
    return match(auth()->user()->panel) {
        'admin'  => redirect('/admin'),
        'doctor' => redirect('/doctor'),
        'nurse'  => redirect('/nurse'),
        'clerk'  => redirect('/clerk'),
        'tech'   => redirect('/tech'),
        'patient'=> redirect('/patient'),
        default  => redirect('/admin/login'),
    };
});
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    /**
     * Save the authenticated user's signature (base64 PNG).
     */
    public function save(Request $request, string $panel)
    {
        $request->validate([
            'signature' => ['required', 'string', 'starts_with:data:image/png;base64,'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('sig_error', 'You must be logged in.');
        }

        // Enforce a reasonable size limit (~300 KB base64)
        if (strlen($request->signature) > 400_000) {
            return redirect()->back()->with('sig_error', 'Signature image is too large. Please try again.');
        }

        $user->signature = $request->signature;
        $user->save();

        // Determine redirect URL back to the signature page for the given panel
        $panelPaths = [
            'admin'  => 'admin',
            'doctor' => 'doctor',
            'nurse'  => 'nurse',
            'clerk'  => 'clerk',
            'tech'   => 'tech',
        ];

        $path = $panelPaths[$panel] ?? $panel;

        return redirect("/{$path}/my-signature")
            ->with('sig_success', 'Your signature has been saved successfully!');
    }
}
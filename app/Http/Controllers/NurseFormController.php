<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class NurseFormController extends Controller
{
    /**
     * GET /forms/vital-sign-monitoring-sheet/{visit}
     *
     * Renders the printable Vital Sign Monitoring Sheet (NUR-014)
     * populated with all Vital records for the given visit.
     */
    public function vitalSignSheet(Visit $visit)
    {
        // Eager-load what the blade needs
        $visit->loadMissing([
            'patient',
            'medicalHistory',
        ]);

        return view('forms.vital-sign-monitoring-sheet', compact('visit'));
    }

    /**
     * GET /forms/iv-bt-sheet/{visit}
     *
     * Renders the printable IV / Blood Transfusion Sheet (NUR-012)
     * populated with all IvFluidEntry records for the given visit.
     */
    public function ivBtSheet(Visit $visit)
    {
        $visit->loadMissing([
            'patient',
            'medicalHistory',
        ]);

        return view('forms.iv-bt-sheet', compact('visit'));
    }
}
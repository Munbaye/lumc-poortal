<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Medication;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // ✅ PRESCRIPTION FUNCTION
    public function prescription($id)
    {
        // kunin patient
        $patient = Patient::findOrFail($id);

        // kunin medications
        $medications = Medication::where('patient_id', $id)->get();

        // return view
        return view('printables.prescription', compact('patient', 'medications'));
    }
}
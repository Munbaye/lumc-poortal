<?php
namespace App\Filament\Patient\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\ResultUpload;
use Filament\Pages\Page;

class MyRecords extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string  $view = 'filament.patient.pages.my-records';
    protected static ?string $title = 'My Medical Records';

    public ?Patient $patient = null;

    public function mount(): void
    {
        $this->patient = $this->getPatient();
    }

    public function getPatient(): ?Patient
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        return Patient::where('contact_number', $user->contact_number)
            ->orWhere('email', $user->email)
            ->first();
    }

    public function getVisits()
    {
        if (!$this->patient) {
            return collect();
        }

        return Visit::where('patient_id', $this->patient->id)
            ->with(['vitals', 'medicalHistory', 'doctorsOrders', 'nursesNotes', 'uploads'])
            ->orderByDesc('registered_at')
            ->get();
    }

    public function getUploads()
    {
        if (!$this->patient) {
            return collect();
        }

        return ResultUpload::where('patient_id', $this->patient->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
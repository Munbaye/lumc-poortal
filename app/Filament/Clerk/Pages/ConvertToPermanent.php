<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\NicuAdmission;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ConvertToPermanent extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string $view = 'filament.clerk.pages.convert-to-permanent';
    protected static ?string $title = 'Convert to Permanent Record';
    protected static ?string $navigationGroup = 'NICU Management';
    protected static ?int $navigationSort = 3;
    
    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from navigation
    }
    
    public ?Visit $visit = null;
    public ?Patient $baby = null;
    public ?NicuAdmission $nicuAdmission = null;
    public ?int $visitId = null;
    
    public function mount(?int $visitId = null): void
    {
        // Get visitId from URL parameter or query string
        $this->visitId = $visitId ?? request()->query('visitId');
        
        if (!$this->visitId) {
            Notification::make()
                ->title('No visit selected')
                ->body('Please select a provisional patient to convert.')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits?tab=provisional');
            return;
        }
        
        $this->visit = Visit::with(['patient', 'nicuAdmission'])->find($this->visitId);
        
        if (!$this->visit) {
            Notification::make()
                ->title('Visit not found')
                ->danger()
                ->send();
            $this->redirect('/clerk/visits');
            return;
        }
        
        $this->baby = $this->visit->patient;
        $this->nicuAdmission = $this->visit->nicuAdmission;
        
        // Ensure this is a provisional record
        if (!$this->baby || !$this->baby->is_provisional) {
            Notification::make()
                ->title('This is already a permanent record or invalid.')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits');
        }
    }
    
    /**
     * Redirect to edit baby information page
     */
    public function editBabyInfo(): void
    {
        if ($this->baby) {
            $this->redirect('/clerk/edit-baby-information?patientId=' . $this->baby->id);
        }
    }
    
    public function convert(): void
    {
        if (!$this->baby) {
            Notification::make()
                ->title('Error')
                ->body('No baby record found.')
                ->danger()
                ->send();
            $this->redirect('/clerk/visits');
            return;
        }
        
        DB::beginTransaction();
        
        try {
            // Convert provisional to permanent
            $this->baby->convertToPermanent(auth()->id());
            
            DB::commit();
            
            Notification::make()
                ->title('✓ Record Converted to Permanent')
                ->body("Permanent Case Number: {$this->baby->case_no}")
                ->success()
                ->send();
                
            $this->redirect('/clerk/visits');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Converting Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
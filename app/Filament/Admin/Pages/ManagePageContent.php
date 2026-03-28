<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteSetting;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class ManagePageContent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Page Content';
    protected static ?string $title           = 'Manage Landing Page Content';
    protected static ?string $navigationGroup = 'Landing Page';
    protected static ?int    $navigationSort  = 2;

    protected static string $view = 'filament.admin.pages.manage-page-content';

    // ── All editable fields ───────────────────────────────────────────────────
    public string $hero_badge             = '';
    public string $hero_heading_1         = '';
    public string $hero_heading_2         = '';
    public string $hero_description       = '';
    public string $hero_amount            = '';
    public string $hero_spirit_word       = '';

    public string $stat_beds              = '';
    public string $stat_beds_label        = '';
    public string $stat_staff             = '';
    public string $stat_staff_label       = '';
    public string $stat_patients          = '';
    public string $stat_patients_label    = '';
    public string $stat_buildings         = '';
    public string $stat_buildings_label   = '';

    public string $about_section_tag      = '';
    public string $about_heading          = '';
    public string $about_para_1           = '';
    public string $about_para_2           = '';
    public string $about_para_3           = '';
    public string $about_card_pct         = '';
    public string $about_card_pct_sub     = '';
    public string $about_card_digital     = '';
    public string $about_card_digital_sub = '';
    public string $about_card_class       = '';
    public string $about_card_class_sub   = '';

    public string $vision_text            = '';
    public string $mission_1              = '';
    public string $mission_2              = '';
    public string $mission_3              = '';
    public string $mission_4              = '';

    public string $dept_surgery_items     = '';
    public string $dept_medicine_items    = '';
    public string $dept_obgyn_items       = '';
    public string $dept_pedia_items       = '';

    public string $contact_address        = '';
    public string $contact_phones         = '';
    public string $contact_email          = '';
    public string $contact_emergency      = '';
    public string $footer_tagline         = '';
    public string $footer_about           = '';
    public string $footer_gov_body        = '';

    // Modal flags
    public bool $showRestoreModal = false;
    public bool $showSaveConfirm  = false;

    // ── Original default values — exactly the original landing page text ───────
    public static function defaults(): array
    {
        return [
            'hero_badge'             => 'Established April 08, 2002',
            'hero_heading_1'         => 'Level 2 Tertiary',
            'hero_heading_2'         => 'Provincial Hospital',
            'hero_description'       => 'A healthcare facility donated by the European Union, serving La Union with the spirit of Agkaysa!',
            'hero_amount'            => '₱650 million',
            'hero_spirit_word'       => 'Agkaysa!',

            'stat_beds'              => '100',
            'stat_beds_label'        => 'Bed Capacity',
            'stat_staff'             => '294',
            'stat_staff_label'       => 'Total Staff',
            'stat_patients'          => '628k+',
            'stat_patients_label'    => 'Patients Served',
            'stat_buildings'         => '27',
            'stat_buildings_label'   => 'Total Buildings',

            'about_section_tag'      => 'Legacy of Care',
            'about_heading'          => 'Our Journey & Transformation',
            'about_para_1'           => 'Established to replace the earthquake-damaged Doña Gregoria Memorial Hospital, LUMC opened on April 08, 2002 — a landmark gift from the European Union.',
            'about_para_2'           => 'Through Republic Act 9259, we became the first Provincial Hospital in the Philippines converted into a non-stock, non-profit local government-controlled corporation.',
            'about_para_3'           => 'Under the Board of Trustees chaired by the Provincial Governor, we serve over 740,000 residents of La Union.',
            'about_card_pct'         => '48%',
            'about_card_pct_sub'     => 'Charity care for indigent patients',
            'about_card_digital'     => 'Digital',
            'about_card_digital_sub' => 'Automated E-NGAS Systems',
            'about_card_class'       => 'CLASS A TO D',
            'about_card_class_sub'   => 'Fair access based on capacity to pay',

            'vision_text'            => 'The La Union Medical Center shall be the center-point for the delivery of quality tertiary medical/surgical care for the people especially in La Union provided in an atmosphere of competent, affordable, compassionate friendly and caring hospital environment.',
            'mission_1'              => 'Comprehensive family medicine with emphasis on preventive and curative care.',
            'mission_2'              => 'Multi-specialty focus towards diagnostic and specialized therapeutic cases.',
            'mission_3'              => 'Training center for medical and paramedical health providers.',
            'mission_4'              => 'Research center for locally based public health concerns.',

            'dept_surgery_items'     => "Orthopedic\nOphthalmology\nOtorhinolaryngology\nNeuro Surgical\nUrology\nThoracic & CV Surgery",
            'dept_medicine_items'    => "Adult Cardiology\nGastroenterology\nNephrology\nAmbulatory Diabetes\nDOTS Clinic",
            'dept_obgyn_items'       => "Gynecologic Oncology\nMaternity Care\nReproductive Health\nFamily Planning",
            'dept_pedia_items'       => "Pediatric Cardiology\nChild Wellness\nImmunization\nNeonatal Intensive Care",

            'contact_address'        => 'Barangay Nazareno, Agoo, La Union, 2504',
            'contact_phones'         => '(072) 607-5541 | (072) 607-5939',
            'contact_email'          => 'pglu_lumc@launion.gov.ph',
            'contact_emergency'      => '0927-728-6330 (24/7)',
            'footer_tagline'         => 'A center of excellence in healthcare, training, and research serving La Union since 2002.',
            'footer_about'           => 'Board of Trustees chaired by the Incumbent Governor of La Union. Established under Republic Act 9259.',
            'footer_gov_body'        => 'Province of La Union',
        ];
    }

    // ── Mount ─────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        $saved    = SiteSetting::allAsArray();
        $defaults = self::defaults();

        foreach ($defaults as $key => $default) {
            $this->$key = (isset($saved[$key]) && $saved[$key] !== '') ? $saved[$key] : $default;
        }
    }

    // ── Save ──────────────────────────────────────────────────────────────────
    public function askSave(): void    { $this->showSaveConfirm = true;  }
    public function cancelSave(): void { $this->showSaveConfirm = false; }

    public function save(): void
    {
        foreach (array_keys(self::defaults()) as $key) {
            SiteSetting::set($key, $this->$key);
        }
        Cache::forget('site_settings_all');
        $this->showSaveConfirm = false;
        Notification::make()->title('Landing page content saved successfully!')->success()->send();
    }

    // ── Restore ───────────────────────────────────────────────────────────────
    public function askRestore(): void    { $this->showRestoreModal = true;  }
    public function cancelRestore(): void { $this->showRestoreModal = false; }

    public function executeRestore(): void
    {
        $defaults = self::defaults();
        foreach ($defaults as $key => $value) {
            SiteSetting::set($key, $value);
            $this->$key = $value;
        }
        Cache::forget('site_settings_all');
        $this->showRestoreModal = false;
        Notification::make()->title('All content restored to original defaults!')->success()->send();
    }
}
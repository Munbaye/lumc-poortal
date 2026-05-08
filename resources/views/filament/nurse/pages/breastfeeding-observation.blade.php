<x-filament-panels::page>
<style>
    .bf-container { max-width: 1400px; margin: 0 auto; }

    .bf-header {
        background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
        border-radius: 12px;
        padding: 18px 24px;
        margin-bottom: 20px;
    }
    .bf-header-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    
    .bf-header-item { text-align: center; }
    
    .bf-header-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #93c5fd;
        margin-bottom: 5px;
    }
    
    .bf-header-value {
        font-size: 1rem;
        font-weight: 700;
        color: white;
    }
    
    .bf-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .dark .bf-section { background: #1f2937; border-color: #374151; }

    .bf-section-header {
        background: #f0fdf4;
        border-bottom: 1px solid #bbf7d0;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dark .bf-section-header { background: #064e3b; border-color: #065f46; }
    
    .bf-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .dark .bf-section-title { color: #86efac; }
    .bf-section-body { padding: 0; }

    /* ── The two-column observation grid ─────────────────────────────────── */
    .obs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    
    .bf-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .dark .bf-card { background: #1f2937; border-color: #374151; }
    
    .bf-card-header {
        padding: 10px 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0 0 8px 0;
        margin: 0 0 8px 0;
        border-bottom: 2px solid;
    }
    .bf-card-header-well {
        background: #f0fdf4;
        color: #166534;
    }
    .dark .bf-card-header-well { background: #064e3b; border-bottom-color: #065f46; color: #86efac; }
    .bf-card-header-diff {
        background: #fff1f2;
        color: #991b1b;
    }
    .dark .bf-card-header-diff { background: #4c0519; border-bottom-color: #7f1d1d; color: #fca5a5; }

    .bf-card-body { padding: 16px; }
    
    .bf-checkbox-group { margin-bottom: 12px; }
    .bf-checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 5px 6px;
        border-radius: 6px;
        font-size: 0.82rem;
        color: #374151;
        transition: background 0.1s;
        line-height: 1.3;
    }
    .bf-checkbox-label:hover { background: #f0fdf4; }
    .dark .bf-checkbox-label:hover { background: #064e3b; }
    
    .bf-checkbox-label input { width: 16px; height: 16px; cursor: pointer; accent-color: #1d4ed8; }
    .bf-checkbox-label span { font-size: 0.85rem; color: #374151; }
    .dark .bf-checkbox-label span { color: #e5e7eb; }
    
    .btn-primary { display:inline-flex; align-items:center; gap:6px; background: #1d4ed8; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }

    .observer-badge {
        background: #e0e7ff;
        color: #3730a3;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .dark .observer-badge { background: #312e81; color: #c7d2fe; }

    .subgroup-label-well { font-weight:700; font-size:0.75rem; color:#166534; margin:12px 0 8px 0; display:flex; align-items:center; gap:5px; }
    .subgroup-label-well:first-of-type { margin-top:0; }
    .subgroup-label-diff { font-weight:700; font-size:0.75rem; color:#991b1b; margin:12px 0 8px 0; display:flex; align-items:center; gap:5px; }
    .subgroup-label-diff:first-of-type { margin-top:0; }
    .dark .subgroup-label-well { color:#86efac; }
    .dark .subgroup-label-diff { color:#fca5a5; }
</style>

<div class="bf-container">

    {{-- ── Patient Header ─────────────────────────────────────────── --}}
    <div class="bf-header">
        <div class="bf-header-grid">
            <div class="bf-header-item">
                <div class="bf-header-label">Mother's Name</div>
                <div class="bf-header-value">{{ $visit->patient->mother_full_name ?? $visit->patient->mother_name ?? '—' }}</div>
            </div>
            <div class="bf-header-item">
                <div class="bf-header-label">Baby's Name</div>
                <div class="bf-header-value">{{ $visit->patient->display_name ?? 'Baby' }}</div>
            </div>
            <div class="bf-header-item">
                <div class="bf-header-label">Date</div>
                <div class="bf-header-value">{{ $currentDate }}</div>
            </div>
            <div class="bf-header-item">
                <div class="bf-header-label">Baby's Age</div>
                <div class="bf-header-value">
                    @if($babyAgeDays > 0)
                        {{ $babyAgeDays }}d {{ $babyAgeHours % 24 }}h
                    @elseif($babyAgeHours > 0)
                        {{ $babyAgeHours }} hour(s)
                    @else
                        Newborn
                    @endif
                </div>
            </div>
        </div>
    </div>


    <form wire:submit="save">

        {{-- ── Observer Info ───────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">
                    <x-heroicon-o-user-circle style="width:16px;height:16px;" />
                    Observation Information
                </span>
            </div>
            <div style="padding: 14px 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div class="observer-badge">
                    <x-heroicon-o-clock style="width:14px;height:14px;" />
                    {{ now()->format('h:i A') }}
                    <span style="opacity:.5;">|</span>
                    <x-heroicon-o-user style="width:14px;height:14px;" />
                    Observed by: {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        {{-- ── Two-column observation form ─────────────────────────── --}}
        <div class="bf-grid">

            {{-- GOING WELL COLUMN --}}
            <div class="bf-card">
                <div class="bf-card-header bf-card-header-well">
                    <x-heroicon-o-check-circle style="width:16px;height:16px;" />
                    Signs that Breastfeeding is Going Well
                </div>
                <div class="bf-card-body">

                    <p class="subgroup-label-well">General</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherHealthy"><span>Mother looks healthy</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherRelaxed"><span>Mother relaxed and comfortable</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherBonding"><span>Signs of bonding between mother and baby</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabyHealthy"><span>Baby looks healthy</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabyCalm"><span>Baby calm and relaxed</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabyRoots"><span>Baby reaches or roots for breast if hungry</span></label>
                    </div>

                    <p class="subgroup-label-well">Breast</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastHealthy"><span>Breast looks healthy</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastNoPain"><span>No pain or discomfort</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastFingersAway"><span>Breast well supported with fingers away from nipple</span></label>
                    </div>

                    <p class="subgroup-label-well">Baby's Position</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionHeadBodyLine"><span>Baby's head and body in line</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionHeldClose"><span>Baby held close to mother's body</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionBodySupported"><span>Baby's whole body supported</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionNoseToNipple"><span>Baby approaches breast, nose to nipple</span></label>
                    </div>

                    <p class="subgroup-label-well">Baby's Attachment</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentMoreAreolaAbove"><span>More areola seen above baby's top lip</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentMouthOpenWide"><span>Baby's mouth open wide</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentLipTurnedOut"><span>Lower lip turned outwards</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentChinTouchesBreast"><span>Baby's chin touches breast</span></label>
                    </div>

                    <p class="subgroup-label-well">Suckling</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingSlowDeepPauses"><span>Slow, deep sucks with pauses</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingCheeksRound"><span>Cheeks round when suckling</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingBabyReleases"><span>Baby releases breast when finished</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingOxytocinReflex"><span>Mother notices signs of oxytocin reflex</span></label>
                    </div>

                </div>
            </div>

            {{-- DIFFICULTY COLUMN --}}
            <div class="bf-card">
                <div class="bf-card-header bf-card-header-diff">
                    <x-heroicon-o-exclamation-triangle style="width:16px;height:16px;" />
                    Signs of Possible Difficulty
                </div>
                <div class="bf-card-body">

                    <p class="subgroup-label-diff">General</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherIll"><span>Mother looks ill or depressed</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherTense"><span>Mother looks tense and uncomfortable</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalMotherNoEyeContact"><span>No mother / baby eye contact</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabySleepyIll"><span>Baby looks sleepy or ill</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabyRestlessCrying"><span>Baby is restless or crying</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="generalBabyNoRoot"><span>Baby does not reach or root</span></label>
                    </div>

                    <p class="subgroup-label-diff">Breast</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastRedSwollenSore"><span>Breasts look red, swollen, or sore</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastPainful"><span>Breast or nipple painful</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="breastFingersOnAreola"><span>Breast held with fingers on areola</span></label>
                    </div>

                    <p class="subgroup-label-diff">Baby's Position</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionNeckTwisted"><span>Baby's neck and head twisted to feed</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionNotHeldClose"><span>Baby not held close</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionHeadNeckOnly"><span>Baby supported by head and neck only</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="positionChinToNipple"><span>Baby approaches breast, lower lip / chin to nipple</span></label>
                    </div>

                    <p class="subgroup-label-diff">Baby's Attachment</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentMoreAreolaBelow"><span>More areola seen below bottom lip</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentMouthNotWide"><span>Baby's mouth not open wide</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentLipsForwardTurnedIn"><span>Lips pointing forward or turned in</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="attachmentChinNotTouching"><span>Baby's chin not touching breast</span></label>
                    </div>

                    <p class="subgroup-label-diff">Suckling</p>
                    <div class="bf-checkbox-group">
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingRapidShallow"><span>Rapid shallow sucks</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingCheeksPulledIn"><span>Cheeks pulled in when suckling</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingMotherTakesOff"><span>Mother takes baby off the breast</span></label>
                        <label class="bf-checkbox-label"><input type="checkbox" wire:model="sucklingNoOxytocinReflex"><span>No signs of oxytocin reflex</span></label>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Submit buttons ──────────────────────────────────────── --}}
        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px; padding-bottom:40px;">
            <button type="button"
                    onclick="window.location.href='/nurse/nurse-chart?visitId={{ $visitId }}'"
                    class="btn-secondary">
                Cancel
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <x-heroicon-o-archive-box-arrow-down style="width:16px;height:16px;" />
                    Save Observation
                </span>
                <span wire:loading>Saving...</span>
            </button>
        </div>


    </form>
</div>
</x-filament-panels::page>
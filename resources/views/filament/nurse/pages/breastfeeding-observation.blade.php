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
    .bf-header-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1px; color: #93c5fd; margin-bottom: 4px; }
    .bf-header-value { font-size: 0.95rem; font-weight: 700; color: white; }

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
    .bf-section-title { font-size: 0.85rem; font-weight: 700; color: #166534; }
    .dark .bf-section-title { color: #86efac; }
    .bf-section-body { padding: 0; }

    /* ── The two-column observation grid ─────────────────────────────────── */
    .obs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .obs-col {
        padding: 16px 18px;
    }
    .obs-col.going-well { border-right: 1px solid #e5e7eb; }
    .dark .obs-col.going-well { border-right-color: #374151; }

    .obs-col-header {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0 0 8px 0;
        margin: 0 0 8px 0;
        border-bottom: 2px solid;
    }
    .obs-col-header.going-well { color: #059669; border-color: #059669; }
    .obs-col-header.difficulty  { color: #dc2626; border-color: #dc2626; }

    /* ── Checkbox items ───────────────────────────────────────────────────── */
    .bf-check-label {
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
    .bf-check-label:hover { background: #f0fdf4; }
    .dark .bf-check-label { color: #e5e7eb; }
    .dark .bf-check-label:hover { background: #064e3b; }
    .bf-check-label input[type="checkbox"] {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        cursor: pointer;
        accent-color: #059669;
    }
    .bf-check-label.difficulty input[type="checkbox"] {
        accent-color: #dc2626;
    }

    /* ── Section divider inside a column ─────────────────────────────────── */
    .obs-divider {
        border: none;
        border-top: 1px dashed #e5e7eb;
        margin: 10px 0;
    }
    .dark .obs-divider { border-color: #374151; }

    /* ── Subsection label ─────────────────────────────────────────────────── */
    .obs-sub-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
        margin: 10px 0 4px 4px;
    }
    .obs-sub-label:first-child { margin-top: 0; }

    .btn-primary { background: #1d4ed8; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
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

    .hint-banner {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 0.78rem;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }
    .dark .hint-banner { background: #431407; border-color: #92400e; color: #fcd34d; }
</style>

<div class="bf-container">
    {{-- Header --}}
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

        {{-- Observer Info --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">👩‍⚕️ Observation Information</span>
            </div>
            <div style="padding: 14px 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div class="observer-badge">
                    <span>🕒 {{ now()->format('h:i A') }}</span>
                    <span>|</span>
                    <span>👤 {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        {{-- Hint --}}
        <div class="hint-banner">
            💡 All "Going Well" signs are pre-checked. Uncheck any that are NOT observed, or check signs of difficulty on the right.
            Checking a difficulty sign will automatically uncheck its corresponding "Going Well" sign.
        </div>

        {{-- ── General ──────────────────────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">👥 General</span>
            </div>
            <div class="bf-section-body">
                <div class="obs-grid">
                    <div class="obs-col going-well">
                        <p class="obs-col-header going-well">✅ Going Well</p>
                        <p class="obs-sub-label">Mother</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalMotherHealthy"> Mother looks healthy</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalMotherRelaxed"> Mother relaxed and comfortable</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalMotherBonding"> Signs of bonding between mother and baby</label>
                        <p class="obs-sub-label">Baby</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalBabyHealthy"> Baby looks healthy</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalBabyCalm"> Baby calm and relaxed</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="generalBabyRoots"> Baby reaches or roots for breast if hungry</label>
                    </div>
                    <div class="obs-col">
                        <p class="obs-col-header difficulty">⚠️ Possible Difficulty</p>
                        <p class="obs-sub-label">Mother</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalMotherIll"> Mother looks ill or depressed</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalMotherTense"> Mother looks tense and uncomfortable</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalMotherNoEyeContact"> No mother / baby eye contact</label>
                        <p class="obs-sub-label">Baby</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalBabySleepyIll"> Baby looks sleepy or ill</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalBabyRestlessCrying"> Baby is restless or crying</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="generalBabyNoRoot"> Baby does not reach or root</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Breast ───────────────────────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">🤱 Breast</span>
            </div>
            <div class="bf-section-body">
                <div class="obs-grid">
                    <div class="obs-col going-well">
                        <p class="obs-col-header going-well">✅ Going Well</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="breastHealthy"> Breast looks healthy</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="breastNoPain"> No pain or discomfort</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="breastFingersAway"> Breast well supported, fingers away from nipple</label>
                    </div>
                    <div class="obs-col">
                        <p class="obs-col-header difficulty">⚠️ Possible Difficulty</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="breastRedSwollenSore"> Breasts look red, swollen, or sore</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="breastPainful"> Breast or nipple painful</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="breastFingersOnAreola"> Breast held with fingers on areola</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Baby's Position ─────────────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">🧸 Baby's Position</span>
            </div>
            <div class="bf-section-body">
                <div class="obs-grid">
                    <div class="obs-col going-well">
                        <p class="obs-col-header going-well">✅ Going Well</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="positionHeadBodyLine"> Baby's head and body in line</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="positionHeldClose"> Baby held close to mother's body</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="positionBodySupported"> Baby's whole body supported</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="positionNoseToNipple"> Baby approaches breast, nose to nipple</label>
                    </div>
                    <div class="obs-col">
                        <p class="obs-col-header difficulty">⚠️ Possible Difficulty</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="positionNeckTwisted"> Baby's neck and head twisted to feed</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="positionNotHeldClose"> Baby not held close</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="positionHeadNeckOnly"> Baby supported by head and neck only</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="positionChinToNipple"> Baby approaches breast, chin / lower lip to nipple</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Baby's Attachment ───────────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">🔗 Baby's Attachment</span>
            </div>
            <div class="bf-section-body">
                <div class="obs-grid">
                    <div class="obs-col going-well">
                        <p class="obs-col-header going-well">✅ Going Well</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="attachmentMoreAreolaAbove"> More areola seen above baby's top lip</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="attachmentMouthOpenWide"> Baby's mouth open wide</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="attachmentLipTurnedOut"> Lower lip turned outwards</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="attachmentChinTouchesBreast"> Baby's chin touches breast</label>
                    </div>
                    <div class="obs-col">
                        <p class="obs-col-header difficulty">⚠️ Possible Difficulty</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="attachmentMoreAreolaBelow"> More areola seen below bottom lip</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="attachmentMouthNotWide"> Baby's mouth not open wide</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="attachmentLipsForwardTurnedIn"> Lips pointing forward or turned in</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="attachmentChinNotTouching"> Baby's chin not touching breast</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Suckling ─────────────────────────────────────────────────────── --}}
        <div class="bf-section">
            <div class="bf-section-header">
                <span class="bf-section-title">🍼 Suckling</span>
            </div>
            <div class="bf-section-body">
                <div class="obs-grid">
                    <div class="obs-col going-well">
                        <p class="obs-col-header going-well">✅ Going Well</p>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="sucklingSlowDeepPauses"> Slow, deep sucks with pauses</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="sucklingCheeksRound"> Cheeks round when suckling</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="sucklingBabyReleases"> Baby releases breast when finished</label>
                        <label class="bf-check-label"><input type="checkbox" wire:model.live="sucklingOxytocinReflex"> Mother notices signs of oxytocin reflex</label>
                    </div>
                    <div class="obs-col">
                        <p class="obs-col-header difficulty">⚠️ Possible Difficulty</p>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="sucklingRapidShallow"> Rapid shallow sucks</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="sucklingCheeksPulledIn"> Cheeks pulled in when suckling</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="sucklingMotherTakesOff"> Mother takes baby off the breast</label>
                        <label class="bf-check-label difficulty"><input type="checkbox" wire:model.live="sucklingNoOxytocinReflex"> No signs of oxytocin reflex</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; padding-bottom: 40px;">
            <button type="button"
                onclick="window.location.href='/nurse/nurse-chart?visitId={{ $visitId }}'"
                class="btn-secondary">
                Cancel
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>💾 Save Observation</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>

    </form>
</div>
</x-filament-panels::page>
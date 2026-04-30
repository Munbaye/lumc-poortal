<x-filament-panels::page>

{{-- ── Stats Row ─────────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:.75rem;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <span style="font-size:.7rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Total Beds</span>
        <span style="font-size:1.875rem;font-weight:700;color:#111827;line-height:1.1;">{{ $this->totalBeds }}</span>
    </div>

    <div style="background:#fff;border:1px solid #bbf7d0;border-radius:.75rem;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <span style="font-size:.7rem;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.05em;">Available</span>
        <span style="font-size:1.875rem;font-weight:700;color:#16a34a;line-height:1.1;">{{ $this->availableBeds }}</span>
    </div>

    <div style="background:#fff;border:1px solid #fecaca;border-radius:.75rem;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <span style="font-size:.7rem;font-weight:600;color:#dc2626;text-transform:uppercase;letter-spacing:.05em;">Occupied</span>
        <span style="font-size:1.875rem;font-weight:700;color:#dc2626;line-height:1.1;">{{ $this->occupiedBeds }}</span>
    </div>

    <div style="background:#fff;border:1px solid #fde68a;border-radius:.75rem;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <span style="font-size:.7rem;font-weight:600;color:#d97706;text-transform:uppercase;letter-spacing:.05em;">Rooms Under Maintenance</span>
        <span style="font-size:1.875rem;font-weight:700;color:#d97706;line-height:1.1;">{{ $this->maintenanceRooms }}</span>
    </div>

</div>

{{-- ── Unassigned Patients Alert ─────────────────────────────────────────── --}}
@php $unassignedCount = $this->unassignedPatients->count(); @endphp
@if($unassignedCount > 0)
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:.75rem;padding:.875rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;">
    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-500 shrink-0" />
    <p style="font-size:.875rem;color:#92400e;margin:0;">
        <strong>{{ $unassignedCount }}</strong> admitted patient(s) not yet assigned to a bed.
    </p>
</div>
@endif

{{-- ── Filters ───────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-3 mb-6">
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search room or ward..."
        class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white w-56 focus:outline-none focus:ring-2 focus:ring-primary-500" />

    <div style="position:relative;display:inline-flex;align-items:center;">
        <select wire:model.live="wardFilter"
            style="border:1px solid #d1d5db;border-radius:.5rem;font-size:.875rem;background:#fff;color:#1f2937;outline:none;padding:0.5rem 2.25rem 0.5rem 0.75rem;cursor:pointer;min-width:8rem;-webkit-appearance:none;appearance:none;">
            <option value="">All Wards</option>
            @foreach($this->wards as $ward)
                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
            @endforeach
        </select>
        <span style="position:absolute;right:0.55rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#6b7280;display:flex;align-items:center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </span>
    </div>

    <div style="position:relative;display:inline-flex;align-items:center;">
        <select wire:model.live="classificationFilter"
            style="border:1px solid #d1d5db;border-radius:.5rem;font-size:.875rem;background:#fff;color:#1f2937;outline:none;padding:0.5rem 2.25rem 0.5rem 0.75rem;cursor:pointer;min-width:10rem;-webkit-appearance:none;appearance:none;">
            <option value="">All Classifications</option>
            <option value="service">Service</option>
            <option value="pay_ward">Pay Ward</option>
            <option value="private">Private</option>
            <option value="aisle">Aisle</option>
        </select>
        <span style="position:absolute;right:0.55rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#6b7280;display:flex;align-items:center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </span>
    </div>
</div>

{{-- ── Wards + Rooms (collapsible) ──────────────────────────────────────── --}}
@forelse($this->rooms as $wardId => $rooms)
    @php $ward = $rooms->first()->ward; @endphp
    <div class="mb-5 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm"
         x-data="{ open: false }">

        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left">
            <div class="flex items-center gap-3 flex-wrap">
                <x-heroicon-o-building-office-2 class="w-5 h-5 text-gray-400 shrink-0" />
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $ward->name }}</span>
                @if($ward->code)
                    <span class="text-xs text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $ward->code }}</span>
                @endif
                <span class="text-xs text-gray-400">{{ $rooms->count() }} room(s)</span>
                @php $maintCount = $rooms->where('is_under_maintenance', true)->count(); @endphp
                @if($maintCount > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 px-2 py-0.5 rounded-full">
                        <x-heroicon-o-wrench-screwdriver class="w-3 h-3" />
                        {{ $maintCount }} under maintenance
                    </span>
                @endif
            </div>
            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="p-4 bg-white dark:bg-gray-900">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($rooms as $room)
                    @php
                        $isPrivate  = $room->classification === 'private';
                        $isMaint    = (bool) $room->is_under_maintenance;
                        $activeBeds = $room->beds->where('is_active', true);
                        $bedCount   = $activeBeds->count();
                        $canAddBed  = ! $isMaint && ! $isPrivate && $bedCount < $room->bed_capacity;
                        $classLabels = ['service' => 'Service', 'pay_ward' => 'Pay Ward', 'private' => 'Private', 'aisle' => 'Aisle'];

                        if ($isMaint) {
                            $cardStyle = 'background-color:#fef2f2;border-color:#fca5a5;';
                        } elseif ($room->classification === 'service') {
                            $cardStyle = 'background-color:#eff6ff;border-color:#bfdbfe;';
                        } elseif ($room->classification === 'pay_ward') {
                            $cardStyle = 'background-color:#fffbeb;border-color:#fde68a;';
                        } elseif ($room->classification === 'private') {
                            $cardStyle = 'background-color:#f0fdf4;border-color:#bbf7d0;';
                        } else {
                            $cardStyle = 'background-color:#f9fafb;border-color:#e5e7eb;';
                        }
                    @endphp

                    <div class="rounded-xl border p-4 shadow-sm flex flex-col gap-3" style="{{ $cardStyle }}">

                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 dark:text-white text-sm leading-tight">Room {{ $room->room_number }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $classLabels[$room->classification] ?? ucfirst($room->classification) }}
                                    &bull; Cap: {{ $room->bed_capacity }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                @if($isMaint)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#fecaca;color:#b91c1c;">
                                        <x-heroicon-o-wrench-screwdriver class="w-3 h-3" /> Maint.
                                    </span>
                                @elseif($isPrivate)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#dcfce7;color:#15803d;">Private</span>
                                @endif
                                <button type="button" wire:click="openRoomMaintenanceModal({{ $room->id }})"
                                    title="{{ $isMaint ? 'Mark Room Operational' : 'Set Room Under Maintenance' }}"
                                    class="p-1 rounded-lg hover:bg-black/10 transition">
                                    @if($isMaint)
                                        <x-heroicon-o-check-circle class="w-4 h-4 text-green-600" />
                                    @else
                                        <x-heroicon-o-wrench-screwdriver class="w-4 h-4 text-gray-400" />
                                    @endif
                                </button>
                            </div>
                        </div>

                        @if($isMaint && $room->maintenance_notes)
                            <p class="text-xs text-red-700 italic rounded p-2" style="background:#fee2e2;border:1px solid #fca5a5;">
                                <x-heroicon-o-information-circle class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5" />
                                {{ $room->maintenance_notes }}
                            </p>
                        @endif

                        <div class="flex flex-col gap-1.5">
                            @forelse($activeBeds as $bed)
                                @php
                                    if ($bed->status === 'available')       { $bedStyle = 'background:#dcfce7;color:#166534;'; }
                                    elseif ($bed->status === 'occupied')    { $bedStyle = 'background:#fee2e2;color:#991b1b;'; }
                                    elseif ($bed->status === 'maintenance') { $bedStyle = 'background:#fef9c3;color:#854d0e;'; }
                                    else                                    { $bedStyle = 'background:#f3f4f6;color:#374151;'; }
                                @endphp
                                <div class="rounded-lg px-2.5 py-1.5" style="{{ $bedStyle }}">
                                    <div class="flex items-center justify-between gap-1">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <x-heroicon-o-inbox class="w-3.5 h-3.5 shrink-0" />
                                            <span class="text-xs font-semibold">{{ $bed->bed_label }}</span>
                                            <span class="text-xs opacity-60 capitalize">{{ $bed->status }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 shrink-0">
                                            @if($bed->status === 'available')
                                                <button type="button" wire:click="openAssignModal({{ $bed->id }})" title="Assign Patient" class="p-0.5 rounded hover:bg-black/10 transition">
                                                    <x-heroicon-o-user-plus class="w-3.5 h-3.5 text-blue-600" />
                                                </button>
                                            @endif
                                            @if($bed->status === 'occupied')
                                                <button type="button" wire:click="openUnassignModal({{ $bed->id }})" title="Unassign Patient" class="p-0.5 rounded hover:bg-black/10 transition">
                                                    <x-heroicon-o-user-minus class="w-3.5 h-3.5 text-orange-500" />
                                                </button>
                                            @endif
                                            @if($bed->status !== 'occupied')
                                                <button type="button" wire:click="openMaintenanceModal({{ $bed->id }})"
                                                    title="{{ $bed->status === 'maintenance' ? 'Mark Available' : 'Set Under Maintenance' }}"
                                                    class="p-0.5 rounded hover:bg-black/10 transition">
                                                    @if($bed->status === 'maintenance')
                                                        <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-green-600" />
                                                    @else
                                                        <x-heroicon-o-wrench-screwdriver class="w-3.5 h-3.5 text-yellow-600" />
                                                    @endif
                                                </button>
                                                @if(! $isPrivate)
                                                    <button type="button" wire:click="openRemoveModal({{ $bed->id }})" title="Remove Bed" class="p-0.5 rounded hover:bg-black/10 transition">
                                                        <x-heroicon-o-x-mark class="w-3.5 h-3.5 text-red-500" />
                                                    </button>
                                                @endif
                                                <button type="button" wire:click="openTransferModal({{ $bed->id }})" title="Transfer to Another Room" class="p-0.5 rounded hover:bg-black/10 transition">
                                                    <x-heroicon-o-arrows-right-left class="w-3.5 h-3.5 text-indigo-500" />
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($bed->status === 'occupied' && $bed->visit?->patient)
                                        <p class="text-xs mt-1 font-medium opacity-80 truncate pl-5">{{ $bed->visit->patient->full_name }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic py-1">No beds added yet.</p>
                            @endforelse
                        </div>

                        @if($canAddBed)
                            <button wire:click="openAddBed({{ $room->id }})"
                                class="mt-1 w-full flex items-center justify-center gap-1.5 text-xs font-medium text-primary-600 border border-dashed border-primary-400 rounded-lg py-1.5 hover:bg-primary-50 transition">
                                <x-heroicon-o-plus class="w-3.5 h-3.5" />
                                Add Bed ({{ $bedCount }}/{{ $room->bed_capacity }})
                            </button>
                        @elseif(! $isPrivate && ! $isMaint && $bedCount >= $room->bed_capacity)
                            <p class="text-xs text-center text-gray-400 italic mt-1">Capacity full ({{ $bedCount }}/{{ $room->bed_capacity }})</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-16 text-gray-400">
        <x-heroicon-o-inbox-stack class="w-8 h-8 mx-auto mb-3 opacity-40" />
        <p class="text-sm">No rooms found. Try adjusting your filters.</p>
    </div>
@endforelse


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MODALS (identical to original nurse page)                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}

<style>
    .nurse-modal-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem; background: rgba(0,0,0,0.5);
    }
    .nurse-modal {
        position: relative; width: 100%; max-width: 26rem;
        background: #fff; border-radius: 0.75rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18); overflow: hidden;
    }
    .dark .nurse-modal { background: #1f2937; }
    .nurse-modal-close {
        position: absolute; top: 0.75rem; right: 0.75rem;
        padding: 0.25rem; border-radius: 0.375rem; color: #9ca3af;
        cursor: pointer; background: transparent; border: none; line-height: 1;
        transition: background 0.15s, color 0.15s;
    }
    .nurse-modal-close:hover { background: #f3f4f6; color: #374151; }
    .nurse-modal-icon-wrap {
        width: 3rem; height: 3rem; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
    }
    .nurse-modal-icon-wrap svg { width: 1.5rem; height: 1.5rem; }
    .nurse-modal-header {
        padding: 2rem 1.5rem 1rem; text-align: center;
        border-bottom: 1px solid #f3f4f6;
    }
    .dark .nurse-modal-header { border-color: #374151; }
    .nurse-modal-title { font-size: 1.0625rem; font-weight: 700; color: #111827; margin: 0 0 0.25rem; line-height: 1.3; }
    .dark .nurse-modal-title { color: #f9fafb; }
    .nurse-modal-subtitle { font-size: 0.8125rem; color: #6b7280; margin: 0; line-height: 1.5; }
    .nurse-modal-body { padding: 1.25rem 1.5rem; }
    .nurse-modal-desc { font-size: 0.875rem; color: #374151; text-align: center; margin: 0; line-height: 1.6; }
    .dark .nurse-modal-desc { color: #d1d5db; }
    .nurse-modal-footer {
        display: flex; align-items: center; justify-content: center; gap: 0.625rem;
        padding: 1rem 1.5rem; border-top: 1px solid #f3f4f6; background: #f9fafb;
    }
    .dark .nurse-modal-footer { border-color: #374151; background: #111827; }
    .nurse-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem;
        padding: 0.5rem 1.125rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 0.5rem; border: none; cursor: pointer;
        transition: opacity 0.15s, box-shadow 0.15s; line-height: 1; white-space: nowrap;
    }
    .nurse-btn:hover { opacity: 0.88; }
    .nurse-btn:disabled { opacity: 0.45; cursor: not-allowed; }
    .nurse-btn-cancel { background: #fff; color: #374151; border: 1px solid #d1d5db; }
    .dark .nurse-btn-cancel { background: #1f2937; color: #d1d5db; border-color: #4b5563; }
    .nurse-btn-cancel:hover { background: #f9fafb; opacity: 1; }
    .dark .nurse-btn-cancel:hover { background: #374151; }
    .nurse-btn-danger  { background: #dc2626; color: #fff; }
    .nurse-btn-primary { background: #2563eb; color: #fff; }
    .nurse-btn-success { background: #16a34a; color: #fff; }
    .nurse-btn-warning { background: #d97706; color: #fff; }
    .nurse-btn-indigo  { background: #4338ca; color: #fff; }
    .nurse-input {
        width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem;
        padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; background: #fff;
        outline: none; transition: border-color 0.15s, box-shadow 0.15s; box-sizing: border-box;
    }
    .nurse-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
    .dark .nurse-input { background: #374151; border-color: #4b5563; color: #f9fafb; }
    .nurse-textarea {
        width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem;
        padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; background: #fff;
        outline: none; resize: none; transition: border-color 0.15s, box-shadow 0.15s; box-sizing: border-box;
    }
    .nurse-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
    .dark .nurse-textarea { background: #374151; border-color: #4b5563; color: #f9fafb; }
    .nurse-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem; }
    .dark .nurse-label { color: #d1d5db; }
    .nurse-field { margin-bottom: 0.875rem; }
    .nurse-field:last-child { margin-bottom: 0; }
    .nurse-error { font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem; }
    .nurse-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem; }
    .nurse-search-results {
        border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;
        max-height: 11rem; overflow-y: auto; margin-top: 0.375rem;
    }
    .dark .nurse-search-results { border-color: #4b5563; }
    .nurse-result-item {
        width: 100%; text-align: left; padding: 0.625rem 0.875rem; font-size: 0.8125rem;
        border: none; border-bottom: 1px solid #f3f4f6; background: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
        transition: background 0.12s;
    }
    .nurse-result-item:last-child { border-bottom: none; }
    .nurse-result-item:hover { background: #f9fafb; }
    .dark .nurse-result-item { background: #1f2937; border-color: #374151; color: #f9fafb; }
    .dark .nurse-result-item:hover { background: #374151; }
    .nurse-result-item.selected { background: #eff6ff; border-left: 3px solid #3b82f6; }
    .dark .nurse-result-item.selected { background: #1e3a5f; }
    .nurse-selected-pill {
        display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem;
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem;
        margin-top: 0.625rem; font-size: 0.8125rem; color: #1d4ed8; font-weight: 500;
    }
    .nurse-info-box { padding: 0.625rem 0.875rem; border-radius: 0.5rem; font-size: 0.8125rem; font-style: italic; }
    .nurse-ward-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 0.5rem; max-height: 12.5rem; overflow-y: auto; }
    .nurse-ward-btn {
        padding: 0.625rem 0.75rem; border-radius: 0.5rem; border: 1.5px solid #e5e7eb;
        background: #fff; font-size: 0.8rem; font-weight: 500; color: #374151;
        text-align: left; cursor: pointer; transition: border-color 0.15s, background 0.15s;
        line-height: 1.3; width: 100%;
    }
    .dark .nurse-ward-btn { background: #1f2937; border-color: #4b5563; color: #d1d5db; }
    .nurse-ward-btn:hover { border-color: #4338ca; background: #eef2ff; color: #4338ca; }
    .nurse-ward-btn.selected { border-color: #4338ca; background: #eef2ff; color: #4338ca; font-weight: 700; }
    .nurse-room-list { display: flex; flex-direction: column; gap: 0.375rem; max-height: 12.5rem; overflow-y: auto; }
    .nurse-room-btn {
        padding: 0.5rem 0.875rem; border-radius: 0.5rem; border: 1.5px solid #e5e7eb;
        background: #fff; font-size: 0.8125rem; font-weight: 500; color: #374151;
        text-align: left; cursor: pointer; transition: border-color 0.15s, background 0.15s;
        display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
        width: 100%; border-left: 3px solid transparent;
    }
    .dark .nurse-room-btn { background: #1f2937; border-color: #4b5563; color: #d1d5db; }
    .nurse-room-btn:hover { border-color: #4338ca; background: #eef2ff; }
    .nurse-room-btn.selected { border-left-color: #4338ca; background: #eef2ff; color: #4338ca; font-weight: 700; }
    .nurse-room-cap { font-size: 0.7rem; color: #9ca3af; white-space: nowrap; }
    .nurse-room-btn.selected .nurse-room-cap { color: #818cf8; }
    .nurse-step-back {
        display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem;
        font-weight: 600; color: #6b7280; background: none; border: none; cursor: pointer;
        padding: 0; margin-bottom: 0.625rem; transition: color 0.15s;
    }
    .nurse-step-back:hover { color: #4338ca; }
    .nurse-selected-room-pill {
        display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem;
        background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 0.5rem;
        margin-top: 0.625rem; font-size: 0.8125rem; color: #4338ca; font-weight: 500;
    }
    .nurse-step-tabs { display: flex; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; margin-bottom: 0.875rem; }
    .dark .nurse-step-tabs { border-color: #4b5563; }
    .nurse-step-tab {
        flex: 1; padding: 0.5rem 0.5rem; font-size: 0.75rem; font-weight: 600; text-align: center;
        color: #9ca3af; background: #f9fafb; border: none; display: flex; align-items: center;
        justify-content: center; gap: 0.375rem; pointer-events: none;
    }
    .dark .nurse-step-tab { background: #1f2937; color: #6b7280; }
    .nurse-step-tab.active { background: #fff; color: #4338ca; }
    .dark .nurse-step-tab.active { background: #374151; color: #818cf8; }
    .nurse-step-tab.done { background: #f0fdf4; color: #16a34a; }
    .nurse-step-tab:not(:last-child) { border-right: 1px solid #e5e7eb; }
    .dark .nurse-step-tab:not(:last-child) { border-color: #4b5563; }
    .nurse-step-num {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.125rem; height: 1.125rem; border-radius: 9999px; font-size: 0.625rem;
        font-weight: 800; background: #e5e7eb; color: #9ca3af; flex-shrink: 0;
    }
    .nurse-step-tab.active .nurse-step-num { background: #4338ca; color: #fff; }
    .nurse-step-tab.done .nurse-step-num { background: #16a34a; color: #fff; }
</style>


{{-- ── ADD BED ──────────────────────────────────────────────────────────── --}}
@if($showAddBedModal)
@php $addRoom = \App\Models\Room::find($selectedRoomId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showAddBedModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            <div class="nurse-modal-icon-wrap" style="background:#ede9fe;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#7c3aed">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
            </div>
            <h2 class="nurse-modal-title">Add Bed</h2>
            <p class="nurse-modal-subtitle">Room <strong>{{ $addRoom?->room_number }}</strong></p>
        </div>
        <div class="nurse-modal-body">
            <div class="nurse-field">
                <label class="nurse-label">Bed Label <span style="color:#ef4444;">*</span></label>
                <input type="text" wire:model="newBedLabel" placeholder="e.g. Bed A, Bed 1, Aisle Bed 3" class="nurse-input" />
                @error('newBedLabel') <p class="nurse-error">{{ $message }}</p> @enderror
                <p class="nurse-hint">Current: {{ $addRoom?->beds()->where('is_active', true)->count() }} / {{ $addRoom?->bed_capacity }}</p>
            </div>
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showAddBedModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            <button wire:click="addBed" class="nurse-btn nurse-btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:.9rem;height:.9rem;">
                    <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                </svg>
                Add Bed
            </button>
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── ASSIGN PATIENT ───────────────────────────────────────────────────── --}}
@if($showAssignModal)
@php $assignBed = \App\Models\Bed::with('room')->find($assignBedId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showAssignModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            <div class="nurse-modal-icon-wrap" style="background:#dbeafe;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#1d4ed8">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM2.046 15.253c-.332 1.19.48 2.247 1.69 2.247h10.528c1.21 0 2.022-1.056 1.69-2.247a6.977 6.977 0 0 0-2.551-3.886 1.75 1.75 0 0 1 .773 1.826l-.47 2.124A1.75 1.75 0 0 1 12 17H6c-.83 0-1.555-.582-1.705-1.398L3.82 13.47a1.75 1.75 0 0 1 .779-1.823 6.977 6.977 0 0 0-2.553 3.606ZM15 8a1 1 0 0 1 1 1v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 1 1 0-2h1V9a1 1 0 0 1 1-1Z" />
                </svg>
            </div>
            <h2 class="nurse-modal-title">Assign Patient</h2>
            <p class="nurse-modal-subtitle">Bed <strong>{{ $assignBed?->bed_label }}</strong> &bull; Room <strong>{{ $assignBed?->room?->room_number }}</strong></p>
        </div>
        <div class="nurse-modal-body">
            <div class="nurse-field">
                <label class="nurse-label">Search Admitted Patient <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        style="position:absolute;left:.625rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#9ca3af;pointer-events:none;">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                    </svg>
                    <input type="text" wire:model.live.debounce.200ms="patientSearch"
                        placeholder="Type name or case number..."
                        class="nurse-input" style="padding-left:2.25rem;" />
                </div>
                @error('assignVisitId') <p class="nurse-error">{{ $message }}</p> @enderror
            </div>

            @if(trim($patientSearch) !== '')
                <div class="nurse-search-results">
                    @forelse($this->searchedPatients as $visit)
                        @php
                            $fullName    = $visit->patient?->full_name ?? '';
                            $caseNo      = $visit->patient?->case_no ?? '';
                            $term        = trim($patientSearch);
                            $highlighted = preg_replace(
                                '/(' . preg_quote($term, '/') . ')/iu',
                                '<mark style="background:#fef08a;color:#111;border-radius:2px;padding:0 1px;">$1</mark>',
                                e($fullName)
                            );
                            $isSelected  = $assignVisitId === $visit->id;
                        @endphp
                        <button type="button" wire:click="selectPatient({{ $visit->id }})"
                            class="nurse-result-item {{ $isSelected ? 'selected' : '' }}">
                            <div>
                                <p style="font-weight:600;color:#111827;margin:0;">{!! $highlighted !!}</p>
                                <p style="font-size:.75rem;color:#6b7280;margin:.125rem 0 0;">{{ $caseNo }}</p>
                            </div>
                            @if($isSelected)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#3b82f6" style="width:1rem;height:1rem;flex-shrink:0;">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </button>
                    @empty
                        <div style="padding:.875rem;text-align:center;font-size:.8125rem;color:#9ca3af;font-style:italic;">
                            No admitted patients found for "{{ $patientSearch }}".
                        </div>
                    @endforelse
                </div>
            @else
                <p style="font-size:.8125rem;color:#9ca3af;font-style:italic;text-align:center;margin:.25rem 0 0;">
                    Start typing to search for an admitted patient.
                </p>
            @endif

            @if($assignVisitId)
                @php $selectedVisit = \App\Models\Visit::with('patient')->find($assignVisitId); @endphp
                <div class="nurse-selected-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;flex-shrink:0;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                    </svg>
                    <span class="truncate">{{ $selectedVisit?->patient?->full_name }} &bull; {{ $selectedVisit?->patient?->case_no }}</span>
                </div>
            @endif
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showAssignModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            <button wire:click="assignPatient" {{ ! $assignVisitId ? 'disabled' : '' }} class="nurse-btn nurse-btn-primary">Assign Patient</button>
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── UNASSIGN PATIENT ─────────────────────────────────────────────────── --}}
@if($showUnassignModal)
@php $unassignBed = \App\Models\Bed::with('visit.patient')->find($unassignBedId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showUnassignModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            <div class="nurse-modal-icon-wrap" style="background:#ffedd5;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#c2410c">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM2.046 15.253c-.332 1.19.48 2.247 1.69 2.247h10.528c1.21 0 2.022-1.056 1.69-2.247a6.977 6.977 0 0 0-2.552-3.886A3.5 3.5 0 0 1 9.5 13H6a3.5 3.5 0 0 1-1.404-.286 6.977 6.977 0 0 0-2.55 3.539ZM15 8a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1Z" />
                </svg>
            </div>
            <h2 class="nurse-modal-title">Unassign Patient</h2>
            <p class="nurse-modal-subtitle">This will free up the bed.</p>
        </div>
        <div class="nurse-modal-body">
            <p class="nurse-modal-desc">
                @if($unassignBed?->visit?->patient)
                    Remove <strong>{{ $unassignBed->visit->patient->full_name }}</strong> from bed <strong>{{ $unassignBed?->bed_label }}</strong>?
                @else
                    Unassign the patient from bed <strong>{{ $unassignBed?->bed_label }}</strong>?
                @endif
            </p>
            <p style="font-size:.8125rem;color:#9ca3af;text-align:center;margin:.5rem 0 0;">The bed will become available for new assignments.</p>
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showUnassignModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            <button wire:click="unassignPatient" class="nurse-btn nurse-btn-danger">Yes, Unassign</button>
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── BED MAINTENANCE TOGGLE ───────────────────────────────────────────── --}}
@if($showMaintenanceModal)
@php $maintBed = \App\Models\Bed::find($maintenanceBedId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showMaintenanceModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            @if($maintBed?->status === 'maintenance')
                <div class="nurse-modal-icon-wrap" style="background:#dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#16a34a">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="nurse-modal-title">Mark Bed Available</h2>
            @else
                <div class="nurse-modal-icon-wrap" style="background:#fef9c3;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ca8a04">
                        <path fill-rule="evenodd" d="M14.5 10a4.5 4.5 0 0 0 4.284-5.882c-.105-.324-.51-.391-.752-.15L15.34 6.66a.454.454 0 0 1-.493.11 3.01 3.01 0 0 1-1.618-1.616.455.455 0 0 1 .11-.494l2.694-2.692c.24-.241.174-.647-.15-.752A4.496 4.496 0 0 0 10 5.5a4.54 4.54 0 0 0 .286 1.587l-7.343 7.343a2.5 2.5 0 1 0 3.536 3.536l7.343-7.343A4.542 4.542 0 0 0 14.5 10Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="nurse-modal-title">Set Bed Under Maintenance</h2>
            @endif
            <p class="nurse-modal-subtitle">Bed <strong>{{ $maintBed?->bed_label }}</strong></p>
        </div>
        <div class="nurse-modal-body">
            @if($maintBed?->status === 'maintenance')
                <p class="nurse-modal-desc">Bed <strong>{{ $maintBed?->bed_label }}</strong> will be marked <strong style="color:#16a34a;">available</strong> and open for patient assignments.</p>
            @else
                <p class="nurse-modal-desc">Bed <strong>{{ $maintBed?->bed_label }}</strong> will be flagged <strong style="color:#ca8a04;">under maintenance</strong> and unavailable for patient assignments.</p>
            @endif
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showMaintenanceModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            @if($maintBed?->status === 'maintenance')
                <button wire:click="toggleBedMaintenance({{ $maintBed?->id }})" class="nurse-btn nurse-btn-success">Mark Available</button>
            @else
                <button wire:click="toggleBedMaintenance({{ $maintBed?->id }})" class="nurse-btn nurse-btn-warning">Set Maintenance</button>
            @endif
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── ROOM MAINTENANCE TOGGLE ──────────────────────────────────────────── --}}
@if($showRoomMaintenanceModal)
@php $maintRoom = \App\Models\Room::find($maintenanceRoomId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showRoomMaintenanceModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            @if($maintRoom?->is_under_maintenance)
                <div class="nurse-modal-icon-wrap" style="background:#dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#16a34a">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="nurse-modal-title">Mark Room Operational</h2>
            @else
                <div class="nurse-modal-icon-wrap" style="background:#fef3c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#d97706">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="nurse-modal-title">Set Room Under Maintenance?</h2>
            @endif
            <p class="nurse-modal-subtitle">Room <strong>{{ $maintRoom?->room_number }}</strong></p>
        </div>
        <div class="nurse-modal-body">
            @if($maintRoom?->is_under_maintenance)
                <p class="nurse-modal-desc">Room <strong>{{ $maintRoom?->room_number }}</strong> will be marked <strong style="color:#16a34a;">operational</strong>.</p>
                @if($maintRoom?->maintenance_notes)
                    <div class="nurse-info-box" style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;margin-top:.75rem;">
                        Current note: {{ $maintRoom->maintenance_notes }}
                    </div>
                @endif
            @else
                <p class="nurse-modal-desc">Nurses will see this room as unavailable. Existing beds will be flagged.</p>
                <div class="nurse-field" style="margin-top:.875rem;">
                    <label class="nurse-label">Reason for Maintenance</label>
                    <textarea wire:model="maintenanceRoomNotes" rows="2"
                        placeholder="e.g. Plumbing repair, electrical work, deep cleaning..."
                        class="nurse-textarea"></textarea>
                </div>
            @endif
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showRoomMaintenanceModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            @if($maintRoom?->is_under_maintenance)
                <button wire:click="toggleRoomMaintenance" class="nurse-btn nurse-btn-success">Mark Operational</button>
            @else
                <button wire:click="toggleRoomMaintenance" class="nurse-btn nurse-btn-danger">Confirm</button>
            @endif
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── REMOVE BED ───────────────────────────────────────────────────────── --}}
@if($showRemoveModal)
@php $removeBed = \App\Models\Bed::with('room')->find($removeBedId); @endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal">
        <button wire:click="$set('showRemoveModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            <div class="nurse-modal-icon-wrap" style="background:#fee2e2;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#dc2626">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="nurse-modal-title">Remove Bed</h2>
            <p class="nurse-modal-subtitle">This action cannot be undone.</p>
        </div>
        <div class="nurse-modal-body">
            <p class="nurse-modal-desc">Are you sure you want to permanently remove bed <strong>{{ $removeBed?->bed_label }}</strong> from Room <strong>{{ $removeBed?->room?->room_number }}</strong>?</p>
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showRemoveModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            <button wire:click="removeBed({{ $removeBed?->id }})" class="nurse-btn nurse-btn-danger">Yes, Remove</button>
        </div>
    </div>
</div>
@endteleport
@endif


{{-- ── TRANSFER BED ─────────────────────────────────────────────────────── --}}
@if($showTransferModal)
@php
    $transferBedObj = \App\Models\Bed::with('room')->find($transferBedId);
    $roomsByWard = $this->allRooms
        ->where('id', '!=', $transferBedObj?->room_id)
        ->groupBy(fn($r) => $r->ward->name);
    $wardNames = $roomsByWard->keys();
@endphp
@teleport('body')
<div class="nurse-modal-overlay">
    <div class="nurse-modal" style="max-width:30rem;" x-data="{
        step: 1,
        selectedWard: '',
        selectedRoomId: '',
        selectedRoomLabel: '',
        selectWard(name) { this.selectedWard = name; this.step = 2; this.selectedRoomId = ''; this.selectedRoomLabel = ''; },
        selectRoom(id, label) { this.selectedRoomId = id; this.selectedRoomLabel = label; },
        goBack() { this.step = 1; this.selectedWard = ''; this.selectedRoomId = ''; this.selectedRoomLabel = ''; }
    }">
        <button wire:click="$set('showTransferModal', false)" class="nurse-modal-close" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1.1rem;height:1.1rem;">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
        <div class="nurse-modal-header">
            <div class="nurse-modal-icon-wrap" style="background:#e0e7ff;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#4338ca">
                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.389Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="nurse-modal-title">Transfer Bed</h2>
            <p class="nurse-modal-subtitle">Moving <strong>{{ $transferBedObj?->bed_label }}</strong> from Room <strong>{{ $transferBedObj?->room?->room_number }}</strong></p>
        </div>
        <div class="nurse-modal-body">
            <div class="nurse-step-tabs">
                <div class="nurse-step-tab" :class="step === 1 ? 'active' : 'done'">
                    <span class="nurse-step-num">
                        <template x-if="step > 1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor" style="width:.65rem;height:.65rem;">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 0 1.414l-5 5a1 1 0 0 1-1.414 0l-2-2a1 1 0 1 1 1.414-1.414L4.586 7.586l4.293-4.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="step === 1"><span>1</span></template>
                    </span>
                    Select Ward
                </div>
                <div class="nurse-step-tab" :class="step === 2 ? 'active' : (step > 2 ? 'done' : '')">
                    <span class="nurse-step-num">2</span>
                    Select Room
                </div>
            </div>

            <div x-show="step === 1">
                <p class="nurse-label" style="margin-bottom:.5rem;">Choose a ward</p>
                <div class="nurse-ward-grid">
                    @foreach($wardNames as $wName)
                        <button type="button"
                            class="nurse-ward-btn"
                            :class="selectedWard === '{{ $wName }}' ? 'selected' : ''"
                            @click="selectWard('{{ $wName }}')">
                            {{ $wName }}
                            <span style="font-size:.7rem;color:#9ca3af;display:block;font-weight:400;margin-top:.125rem;">
                                {{ $roomsByWard[$wName]->count() }} room(s)
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div x-show="step === 2" x-cloak>
                <button type="button" class="nurse-step-back" @click="goBack()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:.875rem;height:.875rem;">
                        <path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                    </svg>
                    Back to wards
                </button>
                <p class="nurse-label" style="margin-bottom:.5rem;">Rooms in <span x-text="selectedWard" style="color:#4338ca;"></span></p>
                <div class="nurse-room-list">
                    @foreach($wardNames as $wName)
                        <template x-if="selectedWard === '{{ $wName }}'">
                            <div>
                                @foreach($roomsByWard[$wName] as $tr)
                                    @php $bedsFilled = $tr->beds()->where('is_active', true)->count(); @endphp
                                    <button type="button"
                                        class="nurse-room-btn"
                                        :class="selectedRoomId === '{{ $tr->id }}' ? 'selected' : ''"
                                        style="margin-bottom:.375rem;"
                                        @click="selectRoom('{{ $tr->id }}', 'Room {{ $tr->room_number }}'); $wire.set('transferRoomId', '{{ $tr->id }}')">
                                        <span>Room {{ $tr->room_number }}</span>
                                        <span class="nurse-room-cap">{{ $bedsFilled }}/{{ $tr->bed_capacity }} beds</span>
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    @endforeach
                </div>
                <div x-show="selectedRoomId !== ''" class="nurse-selected-room-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;flex-shrink:0;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                    <span>Selected: <span x-text="selectedRoomLabel"></span></span>
                </div>
            </div>

            @error('transferRoomId')
                <p class="nurse-error" style="margin-top:.5rem;">{{ $message }}</p>
            @enderror
        </div>
        <div class="nurse-modal-footer">
            <button wire:click="$set('showTransferModal', false)" class="nurse-btn nurse-btn-cancel">Cancel</button>
            <button wire:click="transferBed" class="nurse-btn nurse-btn-indigo"
                x-bind:disabled="selectedRoomId === ''"
                :style="selectedRoomId === '' ? 'opacity:.45;cursor:not-allowed;' : ''">
                Transfer Bed
            </button>
        </div>
    </div>
</div>
@endteleport
@endif

</x-filament-panels::page>
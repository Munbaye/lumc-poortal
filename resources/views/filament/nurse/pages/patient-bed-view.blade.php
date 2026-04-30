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

{{-- ── Info Banner ──────────────────────────────────────────────────────── --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:.75rem;padding:.875rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;">
    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 shrink-0" />
    <p style="font-size:.875rem;color:#1d4ed8;margin:0;">
        This is a <strong>read-only view</strong>. Bed assignments and room management are handled by the <strong>Clerk</strong>.
    </p>
</div>

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
        <span style="position:absolute;right:0.55rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#6b7280;">
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
        <span style="position:absolute;right:0.55rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#6b7280;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </span>
    </div>
</div>

{{-- ── Wards + Rooms (collapsible, read-only) ───────────────────────────── --}}
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
                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
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
                        $isMaint    = (bool) $room->is_under_maintenance;
                        $activeBeds = $room->beds->where('is_active', true);
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
                                @endif
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
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <x-heroicon-o-inbox class="w-3.5 h-3.5 shrink-0" />
                                        <span class="text-xs font-semibold">{{ $bed->bed_label }}</span>
                                        <span class="text-xs opacity-60 capitalize">{{ $bed->status }}</span>
                                    </div>
                                    @if($bed->status === 'occupied' && $bed->visit?->patient)
                                        <p class="text-xs mt-1 font-medium opacity-80 truncate pl-5">
                                            {{ $bed->visit->patient->full_name }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic py-1">No beds added yet.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-16 text-gray-400">
        <x-heroicon-o-inbox-stack class="w-12 h-12 mx-auto mb-3 opacity-40" />
        <p class="text-sm">No rooms found. Try adjusting your filters.</p>
    </div>
@endforelse

</x-filament-panels::page>
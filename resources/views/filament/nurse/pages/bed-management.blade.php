<x-filament-panels::page>

    {{-- ── Stats Row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col gap-1 shadow-sm">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Beds</span>
            <span class="text-3xl font-bold text-gray-800 dark:text-white">{{ $this->totalBeds }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700 p-4 flex flex-col gap-1 shadow-sm">
            <span class="text-xs font-medium text-green-600 uppercase tracking-wide">Available</span>
            <span class="text-3xl font-bold text-green-600">{{ $this->availableBeds }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-700 p-4 flex flex-col gap-1 shadow-sm">
            <span class="text-xs font-medium text-red-500 uppercase tracking-wide">Occupied</span>
            <span class="text-3xl font-bold text-red-500">{{ $this->occupiedBeds }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700 p-4 flex flex-col gap-1 shadow-sm">
            <span class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Rooms Under Maintenance</span>
            <span class="text-3xl font-bold text-yellow-600">{{ $this->maintenanceRooms }}</span>
        </div>
    </div>

    {{-- ── Filters ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap gap-3 mb-6">

        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search room or ward..."
            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white w-56 focus:outline-none focus:ring-2 focus:ring-primary-500"
        />

        <select
            wire:model.live="wardFilter"
            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none pr-8"
            style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z' clip-rule='evenodd' /%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.1rem;"
        >
            <option value="">All Wards</option>
            @foreach($this->wards as $ward)
                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
            @endforeach
        </select>

        <select
            wire:model.live="classificationFilter"
            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none pr-8"
            style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z' clip-rule='evenodd' /%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.1rem;"
        >
            <option value="">All Classifications</option>
            <option value="service">Service</option>
            <option value="pay_ward">Pay Ward</option>
            <option value="private">Private</option>
            <option value="aisle">Aisle</option>
        </select>
    </div>

    {{-- ── Wards + Rooms (collapsible) ─────────────────────────────────────── --}}
    @forelse($this->rooms as $wardId => $rooms)
        @php $ward = $rooms->first()->ward; @endphp

        <div
            class="mb-5 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm"
            x-data="{ open: false }"
        >
            {{-- Ward toggle header --}}
            <button
                type="button"
                @click="open = !open"
                class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left"
            >
                <div class="flex items-center gap-3 flex-wrap">
                    <x-heroicon-o-building-office-2 class="w-5 h-5 text-gray-400 shrink-0" />
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $ward->name }}</span>
                    @if($ward->code)
                        <span class="text-xs text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $ward->code }}</span>
                    @endif
                    <span class="text-xs text-gray-400">{{ $rooms->count() }} room(s)</span>
                    @if($rooms->where('is_under_maintenance', true)->count() > 0)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 px-2 py-0.5 rounded-full">
                            <x-heroicon-o-wrench-screwdriver class="w-3 h-3" />
                            {{ $rooms->where('is_under_maintenance', true)->count() }} under maintenance
                        </span>
                    @endif
                </div>
                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>

            {{-- Room cards --}}
            <div x-show="open" x-collapse class="p-4 bg-white dark:bg-gray-900">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($rooms as $room)
                        @php
                            $isPrivate  = $room->classification === 'private';
                            $isMaint    = $room->is_under_maintenance;
                            $activeBeds = $room->beds->where('is_active', true);
                            $bedCount   = $activeBeds->count();
                            $canAddBed  = ! $isMaint && ! $isPrivate && $bedCount < $room->bed_capacity;

                            // RED card for maintenance, coloured by classification otherwise
                            $cardClass = $isMaint
                                ? 'bg-red-50 border-red-300 dark:bg-red-950 dark:border-red-700'
                                : match($room->classification) {
                                    'service'  => 'bg-blue-50 border-blue-200 dark:bg-blue-950 dark:border-blue-700',
                                    'pay_ward' => 'bg-amber-50 border-amber-200 dark:bg-amber-950 dark:border-amber-700',
                                    'private'  => 'bg-green-50 border-green-200 dark:bg-green-950 dark:border-green-700',
                                    'aisle'    => 'bg-gray-50 border-gray-200 dark:bg-gray-900 dark:border-gray-600',
                                    default    => 'bg-white border-gray-200',
                                };

                            $classLabels = ['service' => 'Service', 'pay_ward' => 'Pay Ward', 'private' => 'Private', 'aisle' => 'Aisle'];
                        @endphp

                        <div class="rounded-xl border {{ $cardClass }} p-4 shadow-sm flex flex-col gap-3">

                            {{-- Room header --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-800 dark:text-white text-sm leading-tight">Room {{ $room->room_number }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $classLabels[$room->classification] ?? ucfirst($room->classification) }}
                                        &bull; Cap: {{ $room->bed_capacity }}
                                    </p>
                                </div>
                                @if($isMaint)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-200 text-red-700 dark:bg-red-900 dark:text-red-300 px-2 py-0.5 rounded-full shrink-0">
                                        <x-heroicon-o-wrench-screwdriver class="w-3 h-3" /> Maint.
                                    </span>
                                @elseif($isPrivate)
                                    <span class="text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 px-2 py-0.5 rounded-full shrink-0">Private</span>
                                @endif
                            </div>

                            {{-- Maintenance note --}}
                            @if($isMaint && $room->maintenance_notes)
                                <p class="text-xs text-red-700 dark:text-red-300 italic bg-red-100 dark:bg-red-900/40 border border-red-200 dark:border-red-800 rounded p-2">
                                    {{ $room->maintenance_notes }}
                                </p>
                            @endif

                            {{-- Beds list --}}
                            <div class="flex flex-col gap-1.5">
                                @forelse($activeBeds as $bed)
                                    @php
                                        $bedColor = match($bed->status) {
                                            'available'   => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'occupied'    => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            default       => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <div class="rounded-lg px-2.5 py-1.5 {{ $bedColor }}">
                                        {{-- Bed row --}}
                                        <div class="flex items-center justify-between gap-1">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <x-heroicon-o-inbox class="w-3.5 h-3.5 shrink-0" />
                                                <span class="text-xs font-semibold">{{ $bed->bed_label }}</span>
                                                <span class="text-xs opacity-60 capitalize">{{ $bed->status }}</span>
                                            </div>

                                            <div class="flex items-center gap-1 shrink-0">

                                                {{-- ASSIGN (available beds only) --}}
                                                @if($bed->status === 'available')
                                                    <button type="button"
                                                        wire:click="openAssignModal({{ $bed->id }})"
                                                        title="Assign Patient"
                                                        class="p-0.5 rounded hover:bg-black/10 transition">
                                                        <x-heroicon-o-user-plus class="w-3.5 h-3.5 text-blue-600" />
                                                    </button>
                                                @endif

                                                {{-- UNASSIGN (occupied beds only) --}}
                                                @if($bed->status === 'occupied')
                                                    <button type="button"
                                                        wire:click="openUnassignModal({{ $bed->id }})"
                                                        title="Unassign Patient"
                                                        class="p-0.5 rounded hover:bg-black/10 transition">
                                                        <x-heroicon-o-user-minus class="w-3.5 h-3.5 text-orange-500" />
                                                    </button>
                                                @endif

                                                {{-- MAINTENANCE TOGGLE (non-occupied only) --}}
                                                @if($bed->status !== 'occupied')
                                                    <button type="button"
                                                        wire:click="openMaintenanceModal({{ $bed->id }})"
                                                        title="{{ $bed->status === 'maintenance' ? 'Mark Available' : 'Mark Under Maintenance' }}"
                                                        class="p-0.5 rounded hover:bg-black/10 transition">
                                                        @if($bed->status === 'maintenance')
                                                            <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-green-600" />
                                                        @else
                                                            <x-heroicon-o-wrench-screwdriver class="w-3.5 h-3.5 text-yellow-600" />
                                                        @endif
                                                    </button>

                                                    {{-- REMOVE BED (non-private, non-occupied) --}}
                                                    @if(! $isPrivate)
                                                        <button type="button"
                                                            wire:click="openRemoveModal({{ $bed->id }})"
                                                            title="Remove Bed"
                                                            class="p-0.5 rounded hover:bg-black/10 transition">
                                                            <x-heroicon-o-x-mark class="w-3.5 h-3.5 text-red-500" />
                                                        </button>
                                                    @endif

                                                    {{-- TRANSFER BED --}}
                                                    <button type="button"
                                                        wire:click="openTransferModal({{ $bed->id }})"
                                                        title="Transfer to Another Room"
                                                        class="p-0.5 rounded hover:bg-black/10 transition">
                                                        <x-heroicon-o-arrows-right-left class="w-3.5 h-3.5 text-indigo-500" />
                                                    </button>
                                                @endif

                                            </div>
                                        </div>

                                        {{-- Patient name under bed label when occupied --}}
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

                            {{-- Add Bed button --}}
                            @if($canAddBed)
                                <button wire:click="openAddBed({{ $room->id }})"
                                    class="mt-1 w-full flex items-center justify-center gap-1.5 text-xs font-medium text-primary-600 dark:text-primary-400 border border-dashed border-primary-400 rounded-lg py-1.5 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                                    <x-heroicon-o-plus class="w-3.5 h-3.5" />
                                    Add Bed ({{ $bedCount }}/{{ $room->bed_capacity }})
                                </button>
                            @elseif(! $isPrivate && ! $isMaint && $bedCount >= $room->bed_capacity)
                                <p class="text-xs text-center text-gray-400 italic mt-1">
                                    Capacity full ({{ $bedCount }}/{{ $room->bed_capacity }})
                                </p>
                            @endif

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


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- ADD BED MODAL                                                          --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showAddBedModal)
        @php $addRoom = \App\Models\Room::find($selectedRoomId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showAddBedModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center shrink-0">
                            <x-heroicon-o-plus class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Add Bed</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Room {{ $addRoom?->room_number }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddBedModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Bed Label <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="newBedLabel"
                            placeholder="e.g. Bed A, Bed 1, Aisle Bed 3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            autofocus
                        />
                        @error('newBedLabel')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                            Current: {{ $addRoom?->beds()->where('is_active', true)->count() }} / {{ $addRoom?->bed_capacity }}
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showAddBedModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="addBed"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 hover:bg-primary-700 text-white transition shadow-sm">
                        Add Bed
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- ASSIGN PATIENT MODAL                                                   --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showAssignModal)
        @php $assignBed = \App\Models\Bed::with('room')->find($assignBedId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showAssignModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center shrink-0">
                            <x-heroicon-o-user-plus class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Assign Patient</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Bed <strong>{{ $assignBed?->bed_label }}</strong>
                                &bull; Room <strong>{{ $assignBed?->room?->room_number }}</strong>
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('showAssignModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Select Admitted Patient <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="assignVisitId"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition appearance-none pr-8"
                            style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z' clip-rule='evenodd' /%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.1rem;"
                        >
                            <option value="">— Select a patient —</option>
                            @foreach($this->unassignedPatients as $visit)
                                <option value="{{ $visit->id }}">
                                    {{ $visit->patient?->full_name }} ({{ $visit->patient?->case_no }})
                                </option>
                            @endforeach
                        </select>
                        @error('assignVisitId')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                        @if($this->unassignedPatients->isEmpty())
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1.5 flex items-center gap-1">
                                <x-heroicon-o-information-circle class="w-3.5 h-3.5 shrink-0" />
                                No admitted patients without a bed assignment at this time.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showAssignModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="assignPatient"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">
                        Assign Patient
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- UNASSIGN PATIENT MODAL                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showUnassignModal)
        @php $unassignBed = \App\Models\Bed::with('visit.patient')->find($unassignBedId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showUnassignModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center shrink-0">
                            <x-heroicon-o-user-minus class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Unassign Patient</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">This action will free up the bed.</p>
                        </div>
                    </div>
                    <button wire:click="$set('showUnassignModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        @if($unassignBed?->visit?->patient)
                            Are you sure you want to remove
                            <strong class="text-gray-900 dark:text-white">{{ $unassignBed->visit->patient->full_name }}</strong>
                            from bed <strong class="text-gray-900 dark:text-white">{{ $unassignBed?->bed_label }}</strong>?
                        @else
                            Are you sure you want to unassign the patient from bed
                            <strong class="text-gray-900 dark:text-white">{{ $unassignBed?->bed_label }}</strong>?
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">The bed will become available for new assignments.</p>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showUnassignModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="unassignPatient"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition shadow-sm">
                        Yes, Unassign
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- BED MAINTENANCE TOGGLE MODAL                                           --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showMaintenanceModal)
        @php $maintBed = \App\Models\Bed::find($maintenanceBedId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showMaintenanceModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        @if($maintBed?->status === 'maintenance')
                            <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center shrink-0">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Mark Bed Available</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Bed <strong>{{ $maintBed?->bed_label }}</strong></p>
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-full bg-yellow-100 dark:bg-yellow-900/50 flex items-center justify-center shrink-0">
                                <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Set Under Maintenance</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Bed <strong>{{ $maintBed?->bed_label }}</strong></p>
                            </div>
                        @endif
                    </div>
                    <button wire:click="$set('showMaintenanceModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    @if($maintBed?->status === 'maintenance')
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Bed <strong class="text-gray-900 dark:text-white">{{ $maintBed?->bed_label }}</strong> will be marked as
                            <strong class="text-green-600">available</strong> and open for patient assignments.
                        </p>
                    @else
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Bed <strong class="text-gray-900 dark:text-white">{{ $maintBed?->bed_label }}</strong> will be flagged as
                            <strong class="text-yellow-600">under maintenance</strong> and unavailable for assignment.
                        </p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showMaintenanceModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="toggleBedMaintenance({{ $maintBed?->id }})"
                        class="{{ $maintBed?->status === 'maintenance' ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-500 hover:bg-yellow-600' }} px-4 py-2 text-sm font-medium rounded-lg text-white transition shadow-sm">
                        {{ $maintBed?->status === 'maintenance' ? 'Mark Available' : 'Set Maintenance' }}
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- REMOVE BED MODAL                                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showRemoveModal)
        @php $removeBed = \App\Models\Bed::with('room')->find($removeBedId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showRemoveModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center shrink-0">
                            <x-heroicon-o-trash class="w-5 h-5 text-red-600 dark:text-red-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Remove Bed</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
                        </div>
                    </div>
                    <button wire:click="$set('showRemoveModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Are you sure you want to permanently remove bed
                        <strong class="text-gray-900 dark:text-white">{{ $removeBed?->bed_label }}</strong>
                        from Room <strong class="text-gray-900 dark:text-white">{{ $removeBed?->room?->room_number }}</strong>?
                    </p>
                    <p class="text-xs text-red-500 dark:text-red-400 mt-2 flex items-center gap-1">
                        <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
                        This will permanently delete the bed record.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showRemoveModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="removeBed({{ $removeBed?->id }})"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 hover:bg-red-700 text-white transition shadow-sm">
                        Yes, Remove
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- TRANSFER BED MODAL                                                     --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showTransferModal)
        @php $transferBedObj = \App\Models\Bed::with('room')->find($transferBedId); @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
            wire:click.self="$set('showTransferModal', false)"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                            <x-heroicon-o-arrows-right-left class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">Transfer Bed</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Moving <strong>{{ $transferBedObj?->bed_label }}</strong> from Room <strong>{{ $transferBedObj?->room?->room_number }}</strong>
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('showTransferModal', false)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Target Room <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="transferRoomId"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition appearance-none pr-8"
                            style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z' clip-rule='evenodd' /%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.1rem;"
                        >
                            <option value="">— Select a room —</option>
                            @foreach($this->allRooms as $tr)
                                @if($tr->id !== $transferBedObj?->room_id)
                                    <option value="{{ $tr->id }}">
                                        {{ $tr->ward->name }} › Room {{ $tr->room_number }}
                                        ({{ $tr->beds()->where('is_active', true)->count() }}/{{ $tr->bed_capacity }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('transferRoomId')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="$set('showTransferModal', false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="transferBed"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-sm">
                        Transfer Bed
                    </button>
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>
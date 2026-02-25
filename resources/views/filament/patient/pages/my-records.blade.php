<x-filament-panels::page>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-green-900 mb-4">📄 My Medical Records</h2>
        @if($patient)
            <p class="text-gray-600">Welcome, {{ $patient->full_name }}</p>
        @else
            <div class="bg-yellow-50 border border-yellow-300 rounded p-4">
                <p class="text-yellow-800">No patient record linked to your account yet. Please contact the clinic.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
<?php
namespace App\Services;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientSearchService
{
    public function search(
        string  $familyName,
        ?string $firstName = null,
        ?string $sex       = null,
        ?string $birthday  = null
    ): Collection {
        $query = Patient::query()
            ->with('latestVisit')
            ->orderBy('family_name');

        // Fuzzy family name search
        // Handles: "dela cruz", "delacruz", "de la cruz"
        $normalized = strtolower(preg_replace('/[\s-]/', '', $familyName));
        $query->where(function ($q) use ($familyName, $normalized) {
            $q->where('family_name', 'LIKE', "%{$familyName}%")
              ->orWhereRaw("REPLACE(REPLACE(LOWER(family_name), ' ', ''), '-', '') LIKE ?",
                          ["%{$normalized}%"]);
        });

        if ($firstName) {
            $query->where('first_name', 'LIKE', "%{$firstName}%");
        }

        if ($sex) {
            $query->where('sex', $sex);
        }

        if ($birthday) {
            // ±1 year tolerance
            $date = \Carbon\Carbon::parse($birthday);
            $query->whereBetween('birthday', [
                $date->copy()->subYear()->format('Y-m-d'),
                $date->copy()->addYear()->format('Y-m-d'),
            ]);
        }

        return $query->limit(10)->get();
    }
}
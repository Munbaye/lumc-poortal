<?php

namespace App\Services;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PatientSearchService
{
    public function search(
        string  $familyName,
        ?string $firstName = null,
        ?string $sex       = null,
        ?string $birthday  = null,
        ?int    $age       = null
    ): Collection {

        // Normalize the search input
        $normFamily = $this->normalize($familyName);

        // Pull a broad candidate set from DB using LIKE on raw + normalized
        $query = Patient::query()->with('latestVisit')->orderBy('family_name');

        // Broad SQL filter — cast a wide net, Levenshtein will tighten it
        $query->where(function ($q) use ($familyName, $normFamily) {
            $q->where('family_name', 'LIKE', '%' . $familyName . '%')
              ->orWhereRaw(
                  "REPLACE(REPLACE(LOWER(family_name), ' ', ''), '-', '') LIKE ?",
                  ['%' . $normFamily . '%']
              )
              // Also grab candidates whose first 3 chars match — needed for Levenshtein
              ->orWhereRaw(
                  "LOWER(LEFT(REPLACE(REPLACE(family_name,' ',''),'-',''), 3)) = ?",
                  [substr($normFamily, 0, 3)]
              );
        });

        // First name filter
        if ($firstName && strlen(trim($firstName)) >= 1) {
            $words = array_filter(explode(' ', strtolower(trim($firstName))));
            if (!empty($words)) {
                $query->where(function ($q) use ($firstName, $words) {
                    $q->where('first_name', 'LIKE', '%' . $firstName . '%');
                    foreach ($words as $word) {
                        if (strlen($word) >= 2) {
                            $q->orWhere('first_name', 'LIKE', '%' . $word . '%');
                        }
                    }
                });
            }
        }

        if ($sex) {
            $query->where('sex', $sex);
        }

        // Birthday ±1 year
        if ($birthday) {
            $date = Carbon::parse($birthday);
            $query->whereBetween('birthday', [
                $date->copy()->subYear()->format('Y-m-d'),
                $date->copy()->addYear()->format('Y-m-d'),
            ]);
        }

        // Age ±2 years (only if no birthday given)
        if ($age !== null && !$birthday) {
            $minAge = max(0, $age - 2);
            $maxAge = $age + 2;
            $query->where(function ($q) use ($minAge, $maxAge) {
                $q->whereBetween('birthday', [
                    now()->subYears($maxAge + 1)->startOfYear()->format('Y-m-d'),
                    now()->subYears($minAge)->endOfDay()->format('Y-m-d'),
                ])->orWhere(function ($q2) use ($minAge, $maxAge) {
                    $q2->whereNull('birthday')
                       ->whereBetween('age', [$minAge, $maxAge]);
                });
            });
        }

        $results = $query->limit(50)->get();

        // ── Post-query Levenshtein fuzzy filter ────────────────────────────────
        // This is what catches "santoz" → "Santos", "Alu" → "Ali", etc.
        $results = $results->filter(function ($patient) use ($normFamily) {
            $stored = $this->normalize($patient->family_name);

            // 1. Direct contains match (already caught by SQL but keep for safety)
            if (str_contains($stored, $normFamily) || str_contains($normFamily, $stored)) {
                return true;
            }

            // 2. Levenshtein on full normalized string
            $distance  = levenshtein($normFamily, $stored);
            $tolerance = $this->getTolerance($normFamily);
            if ($distance <= $tolerance) {
                return true;
            }

            // 3. Levenshtein on prefix (handles long names where only beginning is typed)
            $len         = min(strlen($normFamily), strlen($stored), 8);
            $prefixDist  = levenshtein(substr($normFamily, 0, $len), substr($stored, 0, $len));
            if ($prefixDist <= $tolerance) {
                return true;
            }

            // 4. Soundex match (catches phonetic similarities like "santos"/"santoz")
            if (soundex($normFamily) === soundex($stored)) {
                return true;
            }

            return false;
        });

        return $results->values();
    }

    private function normalize(string $str): string
    {
        return strtolower(preg_replace('/[\s\-]/', '', $str));
    }

    private function getTolerance(string $normalized): int
    {
        $len = strlen($normalized);
        if ($len <= 3) return 1;
        if ($len <= 5) return 2;
        return 2; // max 2 for longer strings to avoid false positives
    }
}
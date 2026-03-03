<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnknownPatientSequence extends Model
{
    protected $fillable = ['year', 'last_sequence'];

    public static function nextForYear(int $year): int
    {
        $record = self::firstOrCreate(
            ['year' => $year],
            ['last_sequence' => 0]
        );

        $record->increment('last_sequence');
        return $record->fresh()->last_sequence;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObPreviousPregnancy extends Model
{
    protected $table = 'ob_previous_pregnancies';

    protected $fillable = [
        'ob_record_id',
        'gravida_order',
        'aog_term',
        'manner_of_delivery',
        'delivery_date',
        'gender',
        'weight_grams',
        'complications',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'weight_grams'  => 'integer',
    ];

    public function obRecord(): BelongsTo
    {
        return $this->belongsTo(ObRecord::class);
    }
}
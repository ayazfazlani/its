<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdDetails extends Model
{
    protected $fillable = [
        'marketing_id',
        'clicks',
        'calls',
        'note',
        'budget_spent',
        'performance'
    ];

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(Marketing::class, 'marketing_id');
    }

    public static function booted()
    {
        static::created(function ($details) {
            $details->marketing->calculatePerformance();
        });
        static::updated(function ($details) {
            $details->marketing->calculatePerformance();
        });
        static::deleted(function ($details) {
            $details->marketing->calculatePerformance();
        });


        static::creating(function ($details) {
            $details->performance = $details->calculateSelfPerformance();
        });

        static::updating(function ($details) {
            $details->performance = $details->calculateSelfPerformance();
        });
    }




    public function calculateSelfPerformance(): float
    {
        $clicks = $this->clicks ?? 0;
        $calls = $this->calls ?? 0;

        return $clicks > 0
            ? round(($calls / $clicks) * 100, 2)
            : 0;
    }
}
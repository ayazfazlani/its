<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    protected $fillable = [
        'name',
        'employee_id',
        'web_url',
        'status',
        'reason',
        'start_date',
        'end_date',
        'performance',
        'reason',
        'payment_status',
        'payment_clearance_date'
    ];
    public function details()
    {
        return $this->hasMany(AdDetails::class);
    }
    protected static function booted()
    {
        static::saved(function ($marketing) {
            if (!$marketing->isDirty('performance')) {
                $marketing->calculatePerformance();
            }
        });
    }
    public function calculatePerformance()
    {
        $details = $this->details()
            ->selectRaw('SUM(clicks) as total_clicks, SUM(calls) as total_calls')
            ->first();

        $total_clicks = $details->total_clicks ?? 0;
        $total_calls = $details->total_calls ?? 0;

        $performance = $total_clicks > 0
            ? round(($total_calls / $total_clicks) * 100, 2)
            : 0;

        $this->performance = $performance;
        $this->saveQuietly();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
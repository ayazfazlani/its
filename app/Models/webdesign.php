<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class webdesign extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
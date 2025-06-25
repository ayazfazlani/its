<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = ['name', 'role', 'branch_id', 'phone', 'email'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

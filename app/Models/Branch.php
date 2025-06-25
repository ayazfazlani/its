<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'location'];




    // Branch.php (Model)
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
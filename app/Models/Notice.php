<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title', 'content', 'created_by', 'target_type'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notice_user');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
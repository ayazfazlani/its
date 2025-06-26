<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeUser extends Model
{
    protected $table = 'notice_user';
    protected $guarded = [];

    public function notice()
    {
        return $this->belongsTo(Notice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

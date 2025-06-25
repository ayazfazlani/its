<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
  use SoftDeletes, HasRoles;

  protected $fillable = [
    'di',
    'user_id',
    'branch_id',
    'department',
    'position',
    'joining_date',
    'cnic',
    'phone',
    'address',
    'status'
  ];

  protected $casts = [
    'joining_date' => 'date',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }



  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }

  public function marketing()
  {
    return $this->hasMany(Marketing::class);
  }

  public function webdesign()
  {
    return $this->hasMany(webdesign::class);
  }
}
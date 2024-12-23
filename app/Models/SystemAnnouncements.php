<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemAnnouncements extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Employee()
    {
        return $this->belongsTo(User::class, 'employee_id','employee_id');
    }
}

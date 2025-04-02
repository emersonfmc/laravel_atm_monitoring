<?php

namespace App\Models\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'description_logs' => 'array'
    ];

    public function Employee()
    {
        return $this->belongsTo(User::class, 'employee_id','employee_id');
    }
}

<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenancePage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}

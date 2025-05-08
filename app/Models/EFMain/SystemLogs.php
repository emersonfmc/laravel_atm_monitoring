<?php

namespace App\Models\EFMain;

use App\Models\User;
use App\Models\EFMain\System;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $connection = 'mysql_connection_ef_main';
    protected $casts = [ 'description_logs' => 'array' ];

    // public function Employee(){
    //     return $this->belongsTo(User::class, 'employee_id','employee_id');
    // }

    // public function System(){
    //     return $this->belongsTo(System::class, 'system_id','id');
    // }
}

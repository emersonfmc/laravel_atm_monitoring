<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataTransactionSequence extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DataUserGroup()
    {
        return $this->belongsTo(DataUserGroup::class, 'user_group_id', 'id');
    }
}


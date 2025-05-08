<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElmTransactionAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DataTransactionSequence(){
        return $this->hasMany(ElmTransactionSequence::class, 'action_id', 'id');
    }
}

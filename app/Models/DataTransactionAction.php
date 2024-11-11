<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataTransactionAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function AtmTransactionSequence()
    {
        return $this->hasMany(DataTransactionSequence::class, 'atm_transaction_actions_id', 'id');
    }
}

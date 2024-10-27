<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmTransactionAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function AtmTransactionSequence()
    {
        return $this->hasMany(AtmTransactionSequence::class, 'atm_transaction_actions_id', 'id');
    }


}

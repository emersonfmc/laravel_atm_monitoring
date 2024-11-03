<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmBanksTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function AtmClientBanks()
    {
        return $this->hasOne(AtmClientBanks::class, 'id','client_banks_id');
    }

    public function Branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id','id');
    }

    public function AtmTransactionAction()
    {
        return $this->belongsTo(AtmTransactionAction::class, 'transaction_actions_id','id');
    }

    public function AtmBanksTransactionApproval()
    {
        return $this->hasMany(AtmBanksTransactionApproval::class, 'banks_transactions_id','id');
    }
}

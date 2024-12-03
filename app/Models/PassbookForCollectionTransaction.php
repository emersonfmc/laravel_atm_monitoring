<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassbookForCollectionTransaction extends Model
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

    public function DataTransactionAction()
    {
        return $this->belongsTo(DataTransactionAction::class, 'transaction_actions_id','id');
    }

    public function PassbookForCollectionTransactionApproval()
    {
        return $this->hasMany(PassbookForCollectionTransactionApproval::class, 'passbook_transactions_id','id');
    }

    public function CancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by_employee_id','employee_id');
    }
}

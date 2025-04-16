<?php

namespace App\Models\Passbook;

use App\Models\User;
use App\Models\EFMain\DataBranch;
use App\Models\EFMain\DataTransactionAction;
use App\Models\ATM\AtmClientBanks;
use App\Models\Passbook\PassbookForCollectionTransactionApproval;

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
        return $this->belongsTo(DataBranch::class,'branch_id','id')->select(['id', 'branch_location','branch_abbreviation']);
    }

    public function DataTransactionAction()
    {
        return $this->belongsTo(DataTransactionAction::class, 'transaction_actions_id','id')
            ->select(['id',
                      'name',
                      'transaction',
                      'status']);
    }

    public function PassbookForCollectionTransactionApproval()
    {
        return $this->hasMany(PassbookForCollectionTransactionApproval::class, 'passbook_transactions_id','id')
            ->select(['id',
                      'passbook_transactions_id',
                      'employee_id',
                      'date_approved',
                      'user_groups_id',
                      'sequence_no',
                      'transaction_actions_id',
                      'status',
                      'type',
                      'created_at']);
    }

    public function CancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by_employee_id','employee_id')
        ->select(['id',
                  'employee_id',
                  'user_group_id',
                  'name']);
    }
}

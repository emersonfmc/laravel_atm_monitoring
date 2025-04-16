<?php

namespace App\Models\ATM;

use App\Models\User;
use App\Models\EFMain\DataTransactionAction;
use App\Models\EFMain\DataUserGroup;

use App\Models\ATM\AtmTransactionApprovalsBalanceLogs;
use App\Models\ATM\AtmBanksTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmBanksTransactionApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function AtmBanksTransaction()
    {
        return $this->belongsTo(AtmBanksTransaction::class, 'banks_transactions_id', 'id')
            ->whereIn('status', ['ON GOING','COMPLETED','CANCELLED']) // Add a filter for the "On Going" status
            ->select([
                'id',
                'branch_id',
                'transaction_actions_id',
                'created_at',
                'client_banks_id',
                'transaction_number',
                'status',
            ]);
    }

    public function DataTransactionAction()
    {
        return $this->belongsTo(DataTransactionAction::class, 'transaction_actions_id','id')
            ->select([
                'id',
                'name'
            ]);
    }

    public function DataUserGroup()
    {
        return $this->belongsTo(DataUserGroup::class, 'user_groups_id','id')
            ->select([
                'id',
                'group_name'
            ]);
    }

    public function Employee()
    {
        return $this->belongsTo(User::class, 'employee_id','employee_id');
    }

    public function AtmTransactionApprovalsBalanceLogs()
    {
        return $this->hasOne(AtmTransactionApprovalsBalanceLogs::class, 'trans_approvals_id','id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmBanksTransactionApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DataUserGroup()
    {
        return $this->belongsTo(DataUserGroup::class, 'user_groups_id','id');
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

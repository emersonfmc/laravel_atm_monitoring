<?php

namespace App\Models\Passbook;

use App\Models\EFMain\DataUserGroup;
use App\Models\EFMain\DataTransactionAction;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassbookForCollectionTransactionApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DataUserGroup()
    {
        return $this->belongsTo(DataUserGroup::class, 'user_groups_id','id');
    }

    public function DataTransactionAction()
    {
        return $this->belongsTo(DataTransactionAction::class, 'transaction_actions_id','id');
    }

    public function Employee()
    {
        return $this->belongsTo(User::class, 'employee_id','employee_id');
    }
}

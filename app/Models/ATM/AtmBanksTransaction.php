<?php

namespace App\Models\ATM;

use App\Models\EFMain\DataBranch;
use App\Models\EFMain\DataTransactionAction;
use Illuminate\Database\Eloquent\Model;
use App\Models\ATM\AtmReleasedRiderImage;
use App\Models\ATM\AtmReleasedClientImage;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ATM\AtmBanksTransactionApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmBanksTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function AtmClientBanks(){
        return $this->hasOne(AtmClientBanks::class, 'id','client_banks_id');
    }

    public function Branch(){
        return $this->belongsTo(DataBranch::class, 'branch_id','id')->select(['id','branch_location','branch_abbreviation']);
    }

    public function DataTransactionAction(){
        return $this->belongsTo(DataTransactionAction::class, 'transaction_actions_id','id')->select(['id','name']);
    }

    public function AtmBanksTransactionApproval(){
        return $this->hasMany(AtmBanksTransactionApproval::class, 'banks_transactions_id','id');
    }

    public function AtmReleasedClientImage(){
        return $this->hasOne(AtmReleasedClientImage::class, 'banks_transactions_id','id');
    }

    public function AtmReleasedRiderImage(){
        return $this->hasOne(AtmReleasedRiderImage::class, 'banks_transactions_id','id');
    }
}

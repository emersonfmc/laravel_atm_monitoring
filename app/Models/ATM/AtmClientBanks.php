<?php

namespace App\Models\ATM;

use App\Models\Branch;

use App\Models\EFMain\DataBranch;
use App\Models\ClientInformation;
use App\Models\Passbook\PassbookForCollectionTransaction;

use Illuminate\Support\Facades\DB;
use App\Models\ATM\AtmBanksTransaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Database\Factories\AtmClientBanksFactory;

class AtmClientBanks extends Model
{
    use HasFactory, SoftDeletes;

    // Used Only for Seeder
    protected static function newFactory(){
        return AtmClientBanksFactory::new();
    }

    protected $guarded = [];

    public function ClientInformation(){
        return $this->belongsTo(ClientInformation::class, 'client_information_id', 'id')
        ->select([
            'id',
            'birth_date',
            'passbook_for_collection',
            'last_name',
            'first_name',
            'middle_name',
            'suffix',
            'created_at'
        ]);
    }

    public function Branch(){
        return $this->belongsTo(DataBranch::class,'branch_id','id')->select(['id', 'branch_location','branch_abbreviation']);
    }

    public function AtmBanksTransaction(){
        return $this->hasMany(AtmBanksTransaction::class, 'client_banks_id', 'id')
                    ->whereIn('status', ['ON GOING','COMPLETED','CANCELLED']) // Add a filter for the "On Going" status
                    ->select([
                        'id',
                        'branch_id',
                        'transaction_actions_id',
                        'client_banks_id',
                        'status',
                    ]);
    }

    public function PassbookForCollectionTransaction(){
        return $this->hasMany(PassbookForCollectionTransaction::class, 'client_banks_id','id')
            ->select([
                'status',
                'id',
                'client_banks_id',
                'status',
            ]);
    }

}

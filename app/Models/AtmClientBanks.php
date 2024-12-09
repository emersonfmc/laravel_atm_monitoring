<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmClientBanks extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ClientInformation()
    {
        return $this->belongsTo(ClientInformation::class, 'client_information_id', 'id')
        ->select([
            'id',
            'branch_id',
            'pension_number',
            'pension_type',
            'pension_account_type',
            'birth_date',
            'passbook_for_collection',
            'last_name',
            'first_name',
            'middle_name',
            'suffix',
            'created_at'
        ]);

    }

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->select(['id', 'branch_location','branch_abbreviation']);
    }

    public function AtmBanksTransaction()
    {
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

    public function PassbookForCollectionTransaction()
    {
        return $this->hasMany(PassbookForCollectionTransaction::class, 'client_banks_id','id')
            ->select([
                'status',
                'id',
                'client_banks_id',
                'status',
            ]);
    }

}

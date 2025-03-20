<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->select(['id', 'branch_location','branch_abbreviation']);
    }

    public function AtmClientBanks()
    {
        return $this->hasMany(AtmClientBanks::class, 'client_information_id','id')
            ->select(['id', 'client_information_id','transaction_number',
                      'atm_type','bank_account_no','bank_name',
                      'pin_no','atm_status','expiration_date',
                      'collection_date','cash_box_no','replacement_count','location','status','created_at']);
    }

    protected $appends = ['fullname']; // This will include fullname in your JSON automatically

    public function getFullnameAttribute() {
        $names = array_filter([$this->first_name, $this->middle_name, $this->last_name, $this->suffix]);
        return implode(' ', $names);
    }

}

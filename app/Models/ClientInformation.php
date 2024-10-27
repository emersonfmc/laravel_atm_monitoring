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
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function AtmClientBanks()
    {
        return $this->hasMany(AtmClientBanks::class, 'client_information_id','id');
    }
}

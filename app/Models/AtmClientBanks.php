<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmClientBanks extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ClientInformation()
    {
        return $this->belongsTo(ClientInformation::class, 'client_information_id','id');
    }
}

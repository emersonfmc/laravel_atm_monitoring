<?php

namespace App\Models\ATM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtmTransactionBalanceLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}

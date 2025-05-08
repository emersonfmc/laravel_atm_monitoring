<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElmBankLists extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}

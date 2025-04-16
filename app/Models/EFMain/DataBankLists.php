<?php

namespace App\Models\EFMain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataBankLists extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $connection = 'mysql_connection_ef_main';
}

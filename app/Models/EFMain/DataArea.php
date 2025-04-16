<?php

namespace App\Models\EFMain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $connection = 'mysql_connection_ef_main';

    public function Company(){
        return $this->belongsTo(DataCompany::class, 'company_id', 'id');
    }

    public function District(){
        return $this->belongsTo(DataDistrict::class, 'district_id', 'id');
    }
}

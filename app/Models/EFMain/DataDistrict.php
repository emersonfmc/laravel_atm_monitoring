<?php

namespace App\Models\EFMain;

use App\Models\EFMain\DataCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataDistrict extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $connection = 'mysql_connection_ef_main';

    public function Company(){
        return $this->belongsTo(DataCompany::class, 'company_id', 'id');
    }

    public function Sector(){
        return $this->belongsTo(DataSector::class, 'sector_id', 'id');
    }
}

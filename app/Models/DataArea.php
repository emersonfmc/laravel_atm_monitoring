<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function District()
    {
        return $this->belongsTo(DataDistrict::class, 'district_id', 'id');
    }
}

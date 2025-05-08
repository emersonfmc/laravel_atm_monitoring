<?php

namespace App\Models\settings;

use App\Models\EFMain\DataUserGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElmDocumentsSequence extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DataUserGroup(){
        return $this->belongsTo(DataUserGroup::class, 'user_group_id', 'id');
    }
}

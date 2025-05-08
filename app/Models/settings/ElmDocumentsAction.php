<?php

namespace App\Models\settings;

use App\Models\Settings\ElmDocumentsSequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElmDocumentsAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function DocumentsSequence(){
        return $this->hasMany(ElmDocumentsSequence::class, 'documents_actions_id', 'id');
    }

}

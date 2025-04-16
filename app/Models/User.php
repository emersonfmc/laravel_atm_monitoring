<?php

namespace App\Models;

use App\Models\EFMain\DataArea;
use App\Models\EFMain\DataBranch;
use App\Models\EFMain\DataCompany;
use App\Models\EFMain\DataDistrict;
use App\Models\EFMain\DataUserGroup;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    public function Company(){
        return $this->belongsTo(DataCompany::class, 'company_id', 'id');
    }

    public function Branch(){
        return $this->belongsTo(DataBranch::class, 'branch_id', 'id');
    }

    public function District(){
        return $this->belongsTo(DataDistrict::class, 'district_code_id', 'id');
    }

    public function Area(){
        return $this->belongsTo(DataArea::class, 'area_code_id', 'id');
    }

    public function UserGroup(){
        return $this->belongsTo(DataUserGroup::class, 'user_group_id', 'id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function Branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function District()
    {
        return $this->belongsTo(DataDistrict::class, 'district_code_id', 'id');
    }

    public function Area()
    {
        return $this->belongsTo(DataArea::class, 'area_code_id', 'id');
    }

    public function UserGroup()
    {
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

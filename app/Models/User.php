<?php

namespace App\Models;

use App\Models\UserRole\UserRole;
use App\Models\UserRole\UserRoleLkp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
#use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable# implements MustVerifyEmail #added implement as per guideline https://laravel.com/docs/8.x/verification
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

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

    /**
     * The relationships that are loaded by default
     *
     * @var array
     */
    protected $with = ['roles'];

    public function roles(): BelongsToMany {
        return $this->belongsToMany(UserRoleLkp::class, 'user_roles', 'user_id', 'role_id')
            ->wherePivot('deleted_at', null);
    }

    public function userRoles(): HasMany {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }
}

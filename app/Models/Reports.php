<?php

namespace App\Models;

use App\Models\UserRole\UserRole;
use App\Models\UserRole\UserRoleLkp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#use Illuminate\Contracts\Auth\MustVerifyEmail;



class Reports extends Authenticatable# implements MustVerifyEmail #added implement as per guideline https://laravel.com/docs/8.x/verification
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'reporting_user_id',
        'package_id',
        'reason',
        'comment',
    ];
    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'reporting_user_id');
    }
}

<?php

namespace App\Models\Shapes;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class UserToken extends Authenticatable# implements MustVerifyEmail #added implement as per guideline https://laravel.com/docs/8.x/verification
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_shapes_tokens';

    protected $fillable = [
        'id',
        'user_id',
        'token'
    ];
    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

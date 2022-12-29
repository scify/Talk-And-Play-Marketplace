<?php

namespace App\Models\Resource;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model {
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lang_id', 'creator_user_id',
        'admin_user_id', 'img_path', 'audio_path',
        'resource_parent_id', 'type_id',
    ];

    public function childrenResources(): HasMany {
        return $this->hasMany(
            Resource::class,
            'resource_parent_id',
            'id'
        );
    }

    public function creator(): HasOne {
        return $this->hasOne(User::class, 'id', 'creator_user_id');
    }

    public function parent(): HasOne {
        return $this->hasOne(Resource::class, 'id', 'card_id');
    }
}

<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourcesPackageRating extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resources_package_ratings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resources_package_id', 'voter_user_id',
        'rating'
    ];
}

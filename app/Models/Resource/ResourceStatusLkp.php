<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourceStatusLkp extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_statuses_lkp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description',
    ];
}

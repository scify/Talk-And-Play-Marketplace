<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourceRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return Resource::class;
    }

}

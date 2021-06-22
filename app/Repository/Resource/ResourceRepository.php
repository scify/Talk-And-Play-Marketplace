<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Repository\Repository;

class ResourceRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return Resource::class;
    }

}

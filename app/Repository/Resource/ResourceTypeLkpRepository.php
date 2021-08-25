<?php


namespace App\Repository\Resource;


use App\Models\Resource\ResourceTypeLkp;
use App\Repository\Repository;

class ResourceTypeLkpRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return ResourceTypeLkp::class;
    }

    public function getResourceTypes(array $ids) {
        return ResourceTypeLkp::whereIn('id', $ids)->get();
    }
}

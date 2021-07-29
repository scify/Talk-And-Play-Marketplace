<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Models\Resource\ResourceStatusLkp;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourcesPackageRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName(): string
    {
        return ResourcesPackage::class;
    }

    public function getResourcesPackage($id)
    {
        return  $this->allWhere(
            [
                'card_id' => $id,
            ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

    public function getApprovedPackagesOfType($type_id)
    {
        return  $this->allWhere(
            [
                'type_id' => $type_id,
                'status_id'=> ResourceStatusesLkp::APPROVED
            ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

}

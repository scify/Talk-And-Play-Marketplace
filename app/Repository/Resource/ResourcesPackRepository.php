<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourcesPackRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return Resource::class;
    }

    function getChildrenCardsWithParent($parentId):Collection{
        return $this->allWhere([
            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'resource_parent_id' => $parentId
        ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

}

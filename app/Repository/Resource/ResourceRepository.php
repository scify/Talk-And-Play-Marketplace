<?php

namespace App\Repository\Resource;

use App\Models\Resource\Resource;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourceRepository extends Repository {
    /**
     * {@inheritDoc}
     */
    public function getModelClassName() {
        return Resource::class;
    }

    public function getChildrenCardsWithParent($parentId): Collection {
        return $this->allWhere([
            //'type_id' => ResourceTypesLkp::COMMUNICATION,
            'resource_parent_id' => $parentId,
        ], ['*'], 'id', 'asc', ['childrenResources', 'creator']);
    }

    public function getLastId() {
        return $this->getModelClassName()::latest()->first()->id;
    }
}

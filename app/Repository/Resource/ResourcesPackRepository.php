<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Models\Resource\ResourcePack;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourcesPackRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return ResourcePack::class;
    }

    public function getResourcePack($id)
    {
        return  $this->allWhere(
            [
                'card_id' => $id,
            ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }




}

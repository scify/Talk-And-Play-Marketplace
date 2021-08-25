<?php


namespace App\Repository\Resource;


use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Models\Resource\ResourceStatusLkp;
use App\Repository\Repository;
use Illuminate\Support\Collection;

class ResourcesPackageRepository extends Repository {

    protected $defaultRelationships = ['coverResource', 'coverResource.childrenResources', 'creator', 'ratings'];

    /**
     * @inheritDoc
     */
    function getModelClassName(): string {
        return ResourcesPackage::class;
    }

    public function getResourcesPackage($id) {
        return $this->allWhere(
            [
                'card_id' => $id,
            ], array('*'), 'id', 'asc', $this->defaultRelationships);
    }

    public function getResourcesPackages(array $type_ids,
                                         int $lang_id = null,
                                         array $status_ids = [ResourceStatusesLkp::APPROVED]) {
        $whereArray = [];
        if ($lang_id)
            $whereArray['lang_id'] = $lang_id;
        return ResourcesPackage
            ::where($whereArray)
            ->whereIn('type_id', $type_ids)
            ->whereIn('status_id', $status_ids)
            ->with($this->defaultRelationships)
            ->get();
    }

}

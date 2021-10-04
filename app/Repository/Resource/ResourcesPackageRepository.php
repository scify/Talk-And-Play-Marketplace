<?php


namespace App\Repository\Resource;


use App\Models\Resource\ResourcesPackage;
use App\Repository\Repository;

class ResourcesPackageRepository extends Repository
{

    protected $defaultRelationships = ['coverResource', 'coverResource.childrenResources', 'creator', 'ratings'];

    /**
     * @inheritDoc
     */
    function getModelClassName(): string
    {
        return ResourcesPackage::class;
    }

    public function getResourcesPackage($id)
    {
        return $this->where([
            'id' => $id,
        ], array('*'), $this->defaultRelationships);
    }

    public function getResourcesPackageWithCoverCard($id)
    {
        return $this->where([
            'card_id' => $id,
        ], array('*'), $this->defaultRelationships);
    }

    public function getResourcesPackages(array $type_ids,
                                         int $user_id = null,
                                         int $lang_id = null,
                                         array $status_ids = [ResourceStatusesLkp::APPROVED]
                                         )
    {
        $whereArray = [];
        if ($lang_id)
            $whereArray['lang_id'] = $lang_id;
        if ($user_id)
            $whereArray['creator_user_id'] = $user_id;
        return ResourcesPackage
            ::where($whereArray)
            ->whereIn('type_id', $type_ids)
            ->whereIn('status_id', $status_ids)
            ->with($this->defaultRelationships)
            ->get();
    }


}

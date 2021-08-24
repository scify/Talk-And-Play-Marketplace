<?php

namespace App\BusinessLogicLayer\Resource;

use App\Repository\Resource\ResourcesPackageRatingRepository;

class ResourcesPackageRatingManager {

    protected $resourcesPackageRatingRepository;

    public function __construct(ResourcesPackageRatingRepository $resourcesPackageRatingRepository) {
        $this->resourcesPackageRatingRepository = $resourcesPackageRatingRepository;
    }


    public function storeOrUpdateRating(int $user_id, int $resources_package_id, int $rating) {
        $data = [
            'voter_user_id' => $user_id,
            'resources_package_id' => $resources_package_id
        ];
        return $this->resourcesPackageRatingRepository->updateOrCreate(
            $data,
            array_merge($data, ['rating' => $rating])
        );
    }

    public function getUserRatingForResourcesPackage(int $user_id, int $resources_package_id) {
        return $this->resourcesPackageRatingRepository->where([
            'voter_user_id' => $user_id,
            'resources_package_id' => $resources_package_id
        ]);
    }

}

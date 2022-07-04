<?php

namespace App\BusinessLogicLayer\Resource;

use App\BusinessLogicLayer\Analytics\AnalyticsEventManager;
use App\Repository\Resource\ResourcesPackageRatingRepository;
use App\Repository\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class ResourcesPackageRatingManager {

    protected ResourcesPackageRatingRepository $resourcesPackageRatingRepository;
    protected AnalyticsEventManager $analyticsEventManager;
    protected UserRepository $userRepository;

    public function __construct(ResourcesPackageRatingRepository $resourcesPackageRatingRepository,
                                AnalyticsEventManager            $analyticsEventManager,
                                UserRepository                   $userRepository) {
        $this->resourcesPackageRatingRepository = $resourcesPackageRatingRepository;
        $this->analyticsEventManager = $analyticsEventManager;
        $this->userRepository = $userRepository;
    }


    public function storeOrUpdateRating(int $user_id, int $resources_package_id, int $rating) {
        $data = [
            'voter_user_id' => $user_id,
            'resources_package_id' => $resources_package_id
        ];
        $this->analyticsEventManager->storeMarketplaceUsageData($this->userRepository->find($user_id), [
            'action' => 'RESOURCE_PACKAGE_RATED_' . $rating,
        ]);
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

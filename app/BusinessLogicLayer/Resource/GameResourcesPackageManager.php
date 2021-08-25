<?php

namespace App\BusinessLogicLayer\Resource;

use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceTypeLkpRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\ViewModels\GameResourcePackagesIndexPage;
use Illuminate\Support\Collection;

class GameResourcesPackageManager extends ResourcesPackageManager {

    protected $resourceTypeLkpRepository;

    public function __construct(ResourceTypeLkpRepository    $resourceTypeLkpRepository,
                                ResourceRepository           $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository   $resourcesPackageRepository,
                                int                          $type_id = -1) {
        $this->resourceTypeLkpRepository = $resourceTypeLkpRepository;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository, $resourcesPackageRepository, $type_id);
    }

    public function getGameResourcesPackageIndexPageVM(): GameResourcePackagesIndexPage {
        return new GameResourcePackagesIndexPage($this->getAllGameResourcesPackageTypes());
    }

    public function getAllGameResourcesPackageTypes(): Collection {
        $data = $this->resourceTypeLkpRepository->getResourceTypes([
                ResourceTypesLkp::RESPONSE_GAME,
                ResourceTypesLkp::TIME_GAME,
                ResourceTypesLkp::SIMILAR_GAME
            ]
        );

        foreach ($data as $datum) {
            $datum->checked = true;
            switch ($datum->id) {
                case ResourceTypesLkp::RESPONSE_GAME:
                    $datum->name = trans("messages.find_response_tagline");
                    break;
                case ResourceTypesLkp::TIME_GAME:
                    $datum->name = trans("messages.find_time_tagline");
                    break;
                case ResourceTypesLkp::SIMILAR_GAME:
                    $datum->name = trans("messages.find_similar_tagline");
                    break;
            }
        }
        return $data;
    }
}

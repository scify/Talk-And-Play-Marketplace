<?php

namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\ReportsRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceTypeLkpRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\ViewModels\CreateEditResourceVM;
use App\ViewModels\DisplayPackageVM;
use Illuminate\Support\Collection;

class TimeGameResourcesPackageManager extends GameResourcesPackageManager {
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;
    protected ReportsRepository  $reportsRepository;

    const MAXIMUM_CARDS_THRESHOLD = 4;
    const TYPE_ID = ResourceTypesLkp::TIME_GAME;

    public function __construct(ResourceTypeLkpRepository $resourceTypeLkpRepository,
                                ResourceRepository $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository $resourcesPackageRepository,
                                ReportsRepository  $reportsRepository) {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        $this->reportsRepository = $reportsRepository;
        parent::__construct($resourceTypeLkpRepository, $resourceRepository, $contentLanguageLkpRepository, $resourcesPackageRepository, $reportsRepository, self::TYPE_ID);
    }

    public function getCreateResourcesPackageViewModel(): CreateEditResourceVM {
        $contentLanguages = $this->getContentLanguagesForResources();

        return new CreateEditResourceVM($contentLanguages,
            new  Resource(),
            new Collection(),
            new ResourcesPackage(),
            self::MAXIMUM_CARDS_THRESHOLD,
            self::TYPE_ID);
    }

    public function getEditResourceViewModel($package): CreateEditResourceVM {
        $contentLanguages = $this->getContentLanguagesForResources();

        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($package->card_id);
//        return new CreateParentVM(self::maximumCardsThreshold, self::type_id)

        return new CreateEditResourceVM($contentLanguages,
            $this->resourceRepository->find($package->card_id),
            $childrenResourceCards,
            $package,
            self::MAXIMUM_CARDS_THRESHOLD,
            self::TYPE_ID);
    }

    public function getApprovedTimeGamePackagesParentResources(): DisplayPackageVM {
        $approvedCommunicationPackages = $this->resourcesPackageRepository->getResourcesPackages([self::TYPE_ID]);

        return new DisplayPackageVM($approvedCommunicationPackages);
    }
}

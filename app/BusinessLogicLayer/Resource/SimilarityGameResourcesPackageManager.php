<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Repository\Resource\ResourceTypesLkp;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourcesPackageRepository;
use App\ViewModels\CreateEditResourceVM;
use App\ViewModels\DisplayPackageVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SimilarityGameResourcesPackageManager extends ResourcesPackageManager
{

    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;
    const maximumCardsThreshold = 4;

    const type_id = ResourceTypesLkp::SIMILAR_GAME;

    public function __construct(ResourceRepository $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository $resourcesPackageRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository, $resourcesPackageRepository, self::type_id);
    }

    public function getCreateResourcesPackageViewModel(): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForResources();
        return new CreateEditResourceVM($contentLanguages,
            new  Resource(),
            new Collection(),
            new ResourcesPackage(),
            self::maximumCardsThreshold,
            self::type_id);
    }

    public function getEditResourceViewModel($id, $package): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForResources();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        return new CreateEditResourceVM($contentLanguages,
            $this->resourceRepository->find($id),
            $childrenResourceCards,
            $package,
            self::maximumCardsThreshold,
            self::type_id);
    }

    public function getApprovedSimilarityGamePackagesParentResources(): DisplayPackageVM
    {

        $approvedCommunicationPackages = $this->resourcesPackageRepository->getApprovedPackagesOfType(self::type_id);
        $parentResources = Collection::empty();


        foreach ($approvedCommunicationPackages as $package) {
            #$parentId = $package->card_id;
            #$parent = $this->resourceRepository->find($parentId);
            $parentResources->push($package->parent);
        }


        return new DisplayPackageVM($parentResources);

    }


}

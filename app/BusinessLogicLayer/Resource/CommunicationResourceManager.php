<?php


namespace App\BusinessLogicLayer\Resource;


use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Support\Collection;

class CommunicationResourceManager extends ResourceManager
{

    protected ResourceRepository $resourceRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    const maximumCardsThreshold = 10;

    public function __construct(ResourceRepository $resourceRepository, ContentLanguageLkpRepository $contentLanguageLkpRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        parent::__construct(
            $this->resourceRepository,
            $this->contentLanguageLkpRepository);
    }

}

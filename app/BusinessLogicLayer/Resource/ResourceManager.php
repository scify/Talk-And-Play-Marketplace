<?php


namespace App\BusinessLogicLayer\Resource;


use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;

class ResourceManager {

    protected ResourceRepository $resourceRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    public function __construct(ResourceRepository $resourceRepository,ContentLanguageLkpRepository  $contentLanguageLkpRepository) {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
    }
}

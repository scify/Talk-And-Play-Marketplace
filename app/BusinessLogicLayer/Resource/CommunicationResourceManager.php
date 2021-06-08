<?php


namespace App\BusinessLogicLayer\Resource;

use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;

class CommunicationResourceManager extends ResourceManager {

    public function getContentLanguagesForCommunicationResources() {
        return $this->contentLanguageLkpRepository->all();
    }

    public function getFirstLevelResourcesWithChildren(int $lang_id) {
        return $this->resourceRepository->allWhere([
            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'status_id' => ResourceStatusesLkp::APPROVED,
            'resource_parent_id' => null,
            'lang_id' => $lang_id
        ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

}

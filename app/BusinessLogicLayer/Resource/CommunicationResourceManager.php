<?php


namespace App\BusinessLogicLayer\Resource;

class CommunicationResourceManager extends ResourceManager {

    public function getContentLanguagesForCommunicationResources() {
        return $this->contentLanguageLkpRepository->all();
    }

}

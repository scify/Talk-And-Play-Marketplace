<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Models\Resource\Resource;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Resource\ResourceRepository;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SimilarityGameResourceManager extends CommunicationResourceManager
{

    public function getContentLanguagesForCommunicationResources()
    {
        return $this->contentLanguageLkpRepository->all();
    }

}

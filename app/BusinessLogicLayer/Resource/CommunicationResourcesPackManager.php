<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourcePack;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Resource\ResourcesPackRepository;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CommunicationResourcesPackManager extends ResourceManager
{
    public ResourcesPackRepository $resourcesPackRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;

    public function __construct(ResourceRepository $resourceRepository, ContentLanguageLkpRepository $contentLanguageLkpRepository, ResourcesPackRepository $resourcesPackRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackRepository = $resourcesPackRepository;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository);
    }

    public function getCreateResourcesPackViewModel(): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForCommunicationResources();
        return new CreateEditResourceVM($contentLanguages, new  Resource(), new Collection(), new ResourcePack());
    }

    public function storeCommunicationResourcesPackage($resource, $lang)
    {


        $this->resourcesPackRepository->create(
            [
                'type_id' => ResourceTypesLkp::COMMUNICATION,
                'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
                'lang_id' => $lang,
                'creator_user_id' => \Illuminate\Support\Facades\Auth::id(),
                'admin_user_id' => null,
                'card_id' => $resource['id']
            ]
        );


    }

    public function getCommunicationResourcesPackage($id){
        return $this->resourcesPackRepository->getResourcePack($id)->first();
    }

    public function approveCommunicationResourcesPackage($id)
    {
        return  $this->resourcesPackRepository->update(
            ['status_id' => ResourceStatusesLkp::APPROVED]
            , $id);
    }





    public function getContentLanguagesForCommunicationResources()
    {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getFirstLevelResourcesWithChildren(int $lang_id)
    {
        return $this->resourceRepository->allWhere([
            'type_id' => ResourceTypesLkp::COMMUNICATION,
//            'status_id' => ResourceStatusesLkp::APPROVED,
            'resource_parent_id' => null,
            'lang_id' => $lang_id
        ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

}

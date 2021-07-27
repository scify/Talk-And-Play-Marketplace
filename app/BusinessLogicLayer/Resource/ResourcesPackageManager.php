<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Resource\ResourcesPackageRepository;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ResourcesPackageManager extends ResourceManager
{
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;
    protected int $type_id;

    public function __construct(ResourceRepository $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository $resourcesPackageRepository,
                                int $type_id=-1)
    {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        $this->type_id = $type_id;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository );
    }



    public function storeResourcePackage(Resource $resource, int $lang)
    {


        $this->resourcesPackageRepository->create(
            [
//                'type_id' => ResourceTypesLkp::COMMUNICATION,
                'type_id' => $this->type_id,
                'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
                'lang_id' => $lang,
                'creator_user_id' => \Illuminate\Support\Facades\Auth::id(),
                'admin_user_id' => null,
                'card_id' => $resource['id']
            ]
        );


    }

    public function getResourcesPackage($id){
        return $this->resourcesPackageRepository->getResourcesPackage($id)->first();
    }

    public function approveResourcesPackage($id)
    {
        return  $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::APPROVED]
            , $id);
    }





    public function getContentLanguagesForResources()
    {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getFirstLevelResourcesWithChildren(int $lang_id)
    {
        return $this->resourceRepository->allWhere([
//            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'type_id' => $this->type_id,
//            'status_id' => ResourceStatusesLkp::APPROVED,
            'resource_parent_id' => null,
//            'lang_id' => $lang_id TODO: search in package
        ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }

}

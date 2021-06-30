<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Models\Resource\Resource;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Resource\ResourceRepository;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CommunicationResourceManager extends ResourceManager
{

    public function getCreateResourceViewModel(): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForCommunicationResources();
        return  new CreateEditResourceVM($contentLanguages, new  Resource(), new Collection());
    }

    public function getEditResourceViewModel($id): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForCommunicationResources();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        return new CreateEditResourceVM($contentLanguages,$this->resourceRepository->find($id), $childrenResourceCards);
    }




    public function getContentLanguagesForCommunicationResources()
    {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getFirstLevelResourcesWithChildren(int $lang_id)
    {
        return $this->resourceRepository->allWhere([
            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'status_id' => ResourceStatusesLkp::APPROVED,
            'resource_parent_id' => null,
            'lang_id' => $lang_id
        ], array('*'), 'id', 'asc', ['childrenResources', 'creator']);
    }



    public function storeCommunicationResource($request)
    {
        $storeArr = [
            "name" => $request['name'],
            "lang_id" => $request['lang'],
            "img_path" => null,
            "audio_path" => null,
            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
            'resource_parent_id' => $request['parentId'] ?: null ,
            'creator_user_id' => \Illuminate\Support\Facades\Auth::id(),
            'admin_user_id' => null
        ];
        $resource = $this->resourceRepository->create($storeArr);
        $resourceFileManager = new CommunicationResourceFileManager();
        $img_path = $resourceFileManager->saveImage($resource->id, $request);
        $audio_path = $resourceFileManager->saveAudio($resource->id, $request);
        return $this->resourceRepository->update([
            'img_path' => $img_path,
            'audio_path' => $audio_path],
            $resource->id);

    }



    public function updateCommunicationResource($request,$id)
    {
        $storeArr = [
            "name" => $request['name'],
            "lang_id" => $request['lang'],
            "img_path" => null,
            "audio_path" => null,
            'type_id' => ResourceTypesLkp::COMMUNICATION,
            'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
            'resource_parent_id' => null,
            'creator_user_id' => \Illuminate\Support\Facades\Auth::id(),
            'admin_user_id' => null
        ];
        $resource = $this->resourceRepository->update($storeArr,$id);
        $resourceFileManager = new CommunicationResourceFileManager();
        if(isset($request['image'])){
            $img_path = $resourceFileManager->saveImage($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'img_path' => $img_path],
                $resource->id);

        }
        if(isset($request['sound'])) {
            $audio_path = $resourceFileManager->saveAudio($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'audio_path' => $audio_path],
                $resource->id);
        }
        return $resource;


    }

}

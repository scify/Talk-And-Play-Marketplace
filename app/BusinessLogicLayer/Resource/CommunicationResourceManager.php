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




    public function storeCommunicationResource($request)
    {
        $storeArr = [
            "name" => $request['name'],
            "lang_id" => $request['lang'],
            "img_path" => null,
            "audio_path" => null,
            'type_id' => ResourceTypesLkp::COMMUNICATION,
//            'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
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
//            'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
            'resource_parent_id' => $request->parentId ? intval($request->parentId) : null,
            'creator_user_id' => \Illuminate\Support\Facades\Auth::id(),
            'admin_user_id' => null
        ];
        $old_resource = $this->resourceRepository->find($id);
        $storeArr['img_path'] = $old_resource['img_path'];
        $storeArr['audio_path'] = $old_resource['audio_path'];
        $resource = $this->resourceRepository->update($storeArr,$id);
        $resourceFileManager = new CommunicationResourceFileManager();
        if(isset($request['image'])){
            $resourceFileManager->deleteResourceImage($old_resource);
            $img_path = $resourceFileManager->saveImage($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'img_path' => $img_path],
                $resource->id);
        }
        if(isset($request['sound'])) {
            $resourceFileManager->deleteResourceAudio($old_resource);
            $audio_path = $resourceFileManager->saveAudio($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'audio_path' => $audio_path],
                $resource->id);
        }

        return $resource;


    }

    public function destroyCommunicationResource($id){
        $resource = $this->resourceRepository->find($id);
        $resourceFileManager = new CommunicationResourceFileManager();
        $resourceFileManager->deleteResourceAudio($resource);
        $resourceFileManager->deleteResourceImage($resource);
        $this->resourceRepository->delete($id);
    }

}

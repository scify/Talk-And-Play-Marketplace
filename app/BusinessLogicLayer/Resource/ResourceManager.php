<?php


namespace App\BusinessLogicLayer\Resource;


use App\Models\Resource\Resource;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceManager {

    protected ResourceRepository $resourceRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    public function __construct(ResourceRepository $resourceRepository,ContentLanguageLkpRepository  $contentLanguageLkpRepository) {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
    }


    public function getContentLanguagesForResources()
    {
        return $this->contentLanguageLkpRepository->all();
    }






    public function storeResource($request)
    {
        $storeArr = [
            "name" => $request['name'],
//            "lang_id" => $request['parentId'] ? $this->resourceRepository->find($request['parentId'])['lang_id'] : $request['lang'],#if parent exists, then inherit its language
            "img_path" => null,
            "audio_path" => null,
//            'type_id' => ResourceTypesLkp::COMMUNICATION,
//            'status_id' => null,
            'resource_parent_id' => $request['parentId'] ?: null,
            'creator_user_id' => Auth::id(),
            'admin_user_id' => null
        ];

        $resource = $this->resourceRepository->create($storeArr);


        $resourceFileManager = new ResourceFileManager();
        $img_path = $resourceFileManager->saveImage($resource->id, $request);
        try{
            $audio_path = $resourceFileManager->saveAudio($resource->id, $request);
        }
        catch(FileNotFoundException $e){
            $audio_path = null;
        }


        return $this->resourceRepository->update([
            'img_path' => $img_path,
            'audio_path' => $audio_path],
            $resource->id);

    }


    public function updateResource($request, $id)
    {
        $storeArr = [
            "name" => $request['name'],
//            "lang_id" => $request['lang'],
            "img_path" => null,
            "audio_path" => null,
//            'type_id' => ResourceTypesLkp::COMMUNICATION,
//            'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
//            'resource_parent_id' => $request->parentId ? intval($request->parentId) : null,
            'creator_user_id' => Auth::id(),
            'admin_user_id' => null
        ];
        $old_resource = $this->resourceRepository->find($id);
        $storeArr['img_path'] = $old_resource['img_path'];
        $storeArr['audio_path'] = $old_resource['audio_path'];
        $resource = $this->resourceRepository->update($storeArr, $id);
        $resourceFileManager = new ResourceFileManager();
        if (isset($request['image'])) {
            $resourceFileManager->deleteResourceImage($old_resource);
            $img_path = $resourceFileManager->saveImage($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'img_path' => $img_path],
                $resource->id);
        }
        if (isset($request['sound'])) {
            $resourceFileManager->deleteResourceAudio($old_resource);
            $audio_path = $resourceFileManager->saveAudio($resource->id, $request);
            $resource = $this->resourceRepository->update([
                'audio_path' => $audio_path],
                $resource->id);
        }

        return $resource;


    }


    public function destroyResource($id)
    {
        $resource = $this->resourceRepository->find($id);
        $resourceFileManager = new ResourceFileManager();
        $resourceFileManager->deleteResourceAudio($resource);
        $resourceFileManager->deleteResourceImage($resource);
        $this->resourceRepository->delete($id);
    }

    public function cloneResource($id, $newParentId){
        $resource = $this->resourceRepository->find($id);
        $fileManager = new ResourceFileManager();
        $storeArr = [
            "name" =>  $resource->name.(' Copy'),
            "img_path" => $fileManager->cloneResourceToDirectory(basename($resource->img_path), "image"),
            "audio_path" =>$fileManager->cloneResourceToDirectory(basename($resource->audio_path), "audio"),
            'resource_parent_id' => $newParentId ?: null,
            'creator_user_id' => Auth::id(),
            'admin_user_id' => null,
        ];
        return $this->resourceRepository->create($storeArr);
    }

}

<?php


namespace App\BusinessLogicLayer\Resource;

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Resource\ResourceRepository;
use Illuminate\Auth;

class CommunicationResourceManager extends ResourceManager
{


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


    public function keepLatinCharactersAndNumbersString($string){
        #todo να το βάλω στον CommunicationResourceManager?
        $newString = preg_replace("/[^A-Za-z0-9.!?\-()]/",'',$string);
        return $newString;
    }

    public function getResourceFileWithoutExtension($string){
        return pathinfo($string)['filename'];
    }

    public function getNormalizedResourceName($resource,$id){
        $resourceFullName = $resource->getClientOriginalName();
        $resourceNameWithoutExtension = $this->getResourceFileWithoutExtension($resourceFullName);
        $resourceNameCleaned = $this->keepLatinCharactersAndNumbersString($resourceNameWithoutExtension);
        $normalizedName = $id . '_' . $resourceNameCleaned. '_' . date("Y-m-d_h:i:s", time()) . '.' . $resource->getClientOriginalExtension();
        return $normalizedName;
    }

    #TODO img,audio nullable
    #TODO edit redirect with ?id in url


    public function storeCommunicationResourceAttachments($request)
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
        $resource = $this->resourceRepository->create($storeArr);

        $fileManager = new CommunicationResourceFileManager();

        $contentImage = $request->file('image');
        $imageFolder = $fileManager->getResourceFileFolder("image");
        $normalizedImageName =$this->getNormalizedResourceName($contentImage,$resource->id);
        $contentImage->storeAs($imageFolder, $normalizedImageName, ['disk' => 'public']);


        $contentSound = $request->file('sound');
        $audioFolder = $fileManager->getResourceFileFolder("audio");
        $normalizedAudioName =$this->getNormalizedResourceName($contentSound,$resource->id);
        $contentSound->storeAs($audioFolder, $normalizedAudioName, ['disk' => 'public']);

        return $this->resourceRepository->update([
            'img_path' => $fileManager->getResourceFullPath($normalizedImageName,"image"),
            'audio_path' => $fileManager->getResourceFullPath($normalizedAudioName,"audio")],
            $resource->id);

    }
}

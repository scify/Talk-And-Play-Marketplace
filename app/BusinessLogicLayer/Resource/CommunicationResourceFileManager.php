<?php


namespace App\BusinessLogicLayer\Resource;


use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\Resource\Resource;
use App\Repository\Resource\ResourceRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CommunicationResourceFileManager
{
    private $IMG_FOLDER;
    private $AUDIO_FOLDER;
    private $RESOURCE_PREFIX_FOLDER;
    private array $SUPPORTED_FILE_TYPES = ["audio","image"];
    public function __construct() {
        $this->IMG_FOLDER = getenv('IMG_FOLDER ') ?: "img/" ;
        $this->AUDIO_FOLDER = getenv('AUDIO_FOLDER ') ?: "audio/" ;
        $this->RESOURCE_PREFIX_FOLDER = getenv('RESOURCE_PREFIX_FOLDER') ?: "resources/" ;
    }

    #todo
    public function deleteResourceImage(Resource $resource){
        Storage::disk('public')->delete($resource->img_path);
    }

    public function deleteResourceAudio(Resource $resource){
        echo $resource->audio_path;
        Storage::disk('public')->delete($resource->audio_path);
    }
    public function keepLatinCharactersAndNumbersString($string){
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
        return $id . '_' . $resourceNameCleaned. '_' . date("Y-m-d_h_i_s", time()) . '.' . $resource->getClientOriginalExtension();
    }

    /**
     * @throws FileNotFoundException
     */
    public  function saveAudio($id, Request $request){
        $contentAudio = $request->file('sound');
        if (!$contentAudio){
            throw(new FileNotFoundException('Audio missing'));
        }
        $audioFolder = $this->getResourceFileFolder("audio");
        $normalizedAudioName =$this->getNormalizedResourceName($contentAudio,$id);
        $contentAudio->storeAs($audioFolder, $normalizedAudioName, ['disk' => 'public']);
        return $this->getResourceFullPath($normalizedAudioName,"audio");
    }

    public  function saveImage($id, Request $request){
        $contentImage = $request->file('image');
        $imageFolder = $this->getResourceFileFolder("image");
        $normalizedImageName =$this->getNormalizedResourceName($contentImage,$id);
        $contentImage->storeAs($imageFolder, $normalizedImageName, ['disk' => 'public']);
        return $this->getResourceFullPath($normalizedImageName,"image");
    }


    public function getResourceFullPath($name,$type): ?string
    {
        assert(in_array($type,$this->SUPPORTED_FILE_TYPES));
        if ($type == "audio") {
            return $this->getResourceFileAudioPath($name);
        } elseif ($type == "image") {
            return $this->getResourceFileImagePath($name);
        }
    }

    public function getResourceFileFolder($type): ?string
    {
        assert(in_array($type,$this->SUPPORTED_FILE_TYPES));
        if ($type == "audio") {
            return $this->getResourceAudioFolder();
        } elseif ($type == "image") {
            return $this->getResourceImageFolder();
        }
    }

    public function getResourceFileAudioPath($name)
    {
        return $this->getResourceAudioFolder() . $name;
    }

    public function getResourceFileImagePath($name)
    {
        return $this->getResourceImageFolder() . $name;
    }



    public function getResourceImageFolder(): string{
        return $this->RESOURCE_PREFIX_FOLDER.$this->IMG_FOLDER;
    }
    public function getResourceAudioFolder(): string{
        return $this->RESOURCE_PREFIX_FOLDER.$this->AUDIO_FOLDER;
    }





}

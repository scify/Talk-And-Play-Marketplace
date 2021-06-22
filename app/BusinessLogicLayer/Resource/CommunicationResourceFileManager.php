<?php


namespace App\BusinessLogicLayer\Resource;


use App\BusinessLogicLayer\UserRole\UserRoleManager;
use Illuminate\Support\Collection;

class CommunicationResourceFileManager
{
    private $IMG_FOLDER;
    private $AUDIO_FOLDER;
    private $RESOURCE_PREFIX_FOLDER;
    private array $SUPPORTED_FILE_TYPES = ["audio","image"];
    public function __construct() {
        $this->IMG_FOLDER = getenv('IMG_FOLDER ') ?: "images/" ;
        $this->AUDIO_FOLDER = getenv('AUDIO_FOLDER ') ?: "audios/" ;
        $this->RESOURCE_PREFIX_FOLDER = getenv('RESOURCE_PREFIX_FOLDER') ?: "storage/" ;

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

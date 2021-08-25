<?php


namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\ViewModels\CreateEditResourceVM;
use App\ViewModels\DisplayPackageVM;
use Illuminate\Support\Collection;

class CommunicationResourcesPackageManager extends ResourcesPackageManager
{
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;
    const maximumCardsThreshold = 10;
    const type_id = ResourceTypesLkp::COMMUNICATION;

    public function __construct(ResourceRepository $resourceRepository, ContentLanguageLkpRepository $contentLanguageLkpRepository, ResourcesPackageRepository $resourcesPackageRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository, $resourcesPackageRepository, self::type_id);
    }


    public function getCreateResourcesPackageViewModel(): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForResources();
        return new CreateEditResourceVM($contentLanguages,
            new  Resource(),
            new Collection(),
            new ResourcesPackage(),
            self::maximumCardsThreshold,
            self::type_id);
    }

    public function getEditResourceViewModel($id, $package): CreateEditResourceVM
    {
        $contentLanguages = $this->getContentLanguagesForResources();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        return new CreateEditResourceVM($contentLanguages,
            $this->resourceRepository->find($id),
            $childrenResourceCards,
            $package,
            self::maximumCardsThreshold,
            ResourceTypesLkp::COMMUNICATION);
    }


    public function getApprovedCommunicationPackagesParentResources(): DisplayPackageVM
    {

        $approvedCommunicationPackages = $this->resourcesPackageRepository->getResourcesPackages([self::type_id]);
        $parentResources = Collection::empty();
        foreach ($approvedCommunicationPackages as $package) {
            $parentId = $package->card_id;
            $parent = $this->resourceRepository->find($parentId);
            $parentResources->push($parent);
        }

        return new DisplayPackageVM($parentResources);

    }


    public function downloadPackage($id, $package)
    {
        $fileManager = new ResourceFileManager();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        $parentResource = $this->resourceRepository->find($id);

        $tmpDir = sys_get_temp_dir().'/'.'package-'. $id;
        if(is_dir($tmpDir) == false) {
            mkdir($tmpDir, 0700);
        }

        $header =
<<<XML
<?xml version='1.0'?>
<category name="" enabled="true" languages="">
</category>
XML;
        $xmlTemplate = simplexml_load_string($header);
        $xmlTemplate['name'] = $parentResource->name;

        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;
        $xmlTemplate['languages'] = $lang;


        $image_name = basename($parentResource->img_path);
        $audio_name =  basename($parentResource->audio_path);
        $fileManager->copyResourceToDirectory($tmpDir, $image_name, "image");
        $fileManager->copyResourceToDirectory($tmpDir, $audio_name, "audio");


        $xmlTemplate->addChild('image',$image_name);
        $xmlTemplate->addChild('sound', $audio_name);
        $xmlTemplate->addChild('categories');
        $categories = $xmlTemplate->categories;
        foreach($childrenResourceCards as $child){

            $category = $categories->addChild('category');
            $image_name = basename($child->img_path);
            $audio_name =  basename($child->audio_path);
            $fileManager->copyResourceToDirectory($tmpDir, $image_name, "image");
            $fileManager->copyResourceToDirectory($tmpDir, $audio_name, "audio");
            $category->addChild('image',$image_name);
            $category->addChild('sound', $audio_name);
        }

        $xmlTemplate->asXML($tmpDir.'/structure.xml');

        $zipName = basename($tmpDir).".zip";
        $fileManager->getCreateZip($zipName, $tmpDir);

        //then send the headers to force download the zip file
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$zipName");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($zipName);
        exit(0);
    }


}

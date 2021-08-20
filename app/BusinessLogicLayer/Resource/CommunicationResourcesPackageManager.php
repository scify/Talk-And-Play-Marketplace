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
use App\ViewModels\DisplayPackageVM;
use App\ViewModels\ResourcePackagesVM;
use Illuminate\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ZipArchive;
use function PHPUnit\Framework\directoryExists;

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

        $approvedCommunicationPackages = $this->resourcesPackageRepository->getApprovedPackagesOfType(self::type_id);
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

        $fileManager = new CommunicationResourceFileManager();

        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        $parentResource = $this->resourceRepository->find($id);
        $tmpDir = sys_get_temp_dir().'/'.'package-'.strval($id);
        if(directoryExists($tmpDir) == false) {
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
        copy(storage_path('app/public').'/'.$fileManager->getResourceFullPath($image_name,"image"), $tmpDir.'/'.$image_name);
        copy(storage_path('app/public').'/'.$fileManager->getResourceFullPath($audio_name,"audio"), $tmpDir.'/'.$audio_name);

        $xmlTemplate->addChild('image',$image_name);
        $xmlTemplate->addChild('sound', $audio_name);
        $xmlTemplate->addChild('categories');
        $categories = $xmlTemplate->categories;
        foreach($childrenResourceCards as $child){

            $category = $categories->addChild('category');
            $image_name = basename($child->img_path);
            $audio_name =  basename($child->audio_path);
            copy(storage_path('app/public').'/'.$fileManager->getResourceFullPath($image_name,"image"), $tmpDir.'/'.$image_name);
            copy(storage_path('app/public').'/'.$fileManager->getResourceFullPath($audio_name,"audio"), $tmpDir.'/'.$audio_name);
            $category->addChild('image',$image_name);
            $category->addChild('sound', $audio_name);
        }
        header('Content-disposition: attachment; filename="structure.xml"');
        header('Content-type: "text/xml"; charset="utf8"');

        $xmlTemplate->asXML($tmpDir.'/structure.xml');

        // Enter the name to creating zipped directory

        $zipName = basename($tmpDir).'.zip';
        $zip = new ZipArchive;
        if($zip -> open($zipName, ZipArchive::CREATE ) === TRUE) {
            // Store the path into the variable
            $dir = opendir($tmpDir);
            while($file = readdir($dir)) {
                if(is_file($tmpDir.'/'.$file)) {
                    $zip -> addFile($tmpDir.'/'.$file, $file);
                }
            }
            $zip ->close();
        }

        //then send the headers to force download the zip file
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$zipName");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($zipName);
        exit(0);
    }


}

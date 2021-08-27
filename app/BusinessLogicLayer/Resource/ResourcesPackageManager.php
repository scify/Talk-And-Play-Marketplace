<?php


namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;

class ResourcesPackageManager extends ResourceManager {
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected int $type_id;

    public function __construct(ResourceRepository           $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository   $resourcesPackageRepository,
                                int                          $type_id = -1) {
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        $this->type_id = $type_id;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository);
    }


    public function storeResourcePackage(Resource $resource, int $lang) :ResourcesPackage {

        if ($this->type_id === -1)
            throw new InvalidArgumentException("Type id must be a positive integer.");

        return $this->resourcesPackageRepository->create(
            [
                'type_id' => $this->type_id,
                'status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL,
                'lang_id' => $lang,
                'creator_user_id' => Auth::id(),
                'admin_user_id' => null,
                'card_id' => $resource['id']
            ]
        );


    }

    public function getResourcesPackage($id) {
        return $this->resourcesPackageRepository->getResourcesPackage($id);
    }

    public function approveResourcesPackage($id) {
        return $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::APPROVED]
            , $id);
    }


    public function getContentLanguagesForResources() {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getResourcesPackages(int $lang_id, array $type_ids, array $status_ids) {
        return $this->resourcesPackageRepository->getResourcesPackages($type_ids, $lang_id, $status_ids);
    }

    public function downloadGamePackage($package, $gameType = "") {
        $fileManager = new ResourceFileManager();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($package->card_id);
        $parentResource = $this->resourceRepository->find($package->card_id);
        $packageDir = 'resources_packages/zips/package-' . $package->id;
        if (is_dir($packageDir) == false) {
            Storage::makeDirectory($packageDir);
        }
        $header =
            <<<XML
<?xml version='1.0'?>
<game name="" enabled="true" gameType="$gameType">
</game>
XML;
        $xmlTemplate = simplexml_load_string($header);
        $xmlTemplate['name']  = str_replace(array("?","!",",",";"), "",  $parentResource->name );



//        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;
//        $xmlTemplate['languages'] = $lang;

        $imageName = basename($parentResource->img_path);
        $dirPath = Storage::path($packageDir);
        $fileManager->copyResourceToDirectory($dirPath, $imageName, "image");
        $xmlTemplate->addChild('image', $imageName);
        $xmlTemplate->addChild('difficulty', 3);
        $xmlTemplate->addChild('gameImages');
        $gameImages = $xmlTemplate->gameImages;

        foreach ($childrenResourceCards as $i => $child) {
            $imageName = basename($child->img_path);
            $fileManager->copyResourceToDirectory($dirPath, $imageName, "image");
            $gameImage = $gameImages->addChild('gameImage', $imageName);
            $gameImage->addAttribute('enabled', "true");
            $gameImage->addAttribute('order', strval($i + 1));
        }

        $xmlTemplate->asXML($dirPath . '/structure.xml');
        $zipName = basename($dirPath . ".zip");
        $fileManager->getCreateZip( $dirPath);
        Storage::makeDirectory("resources_packages/zips");
        Storage::deleteDirectory($packageDir);
        $headers = [
            "Content-type: application/zip",
            "Content-Disposition: attachment; filename=$zipName",
            "Pragma: no-cache",
            "Expires: 0"
        ] ;
//       return Storage::download("resources_packages/zips/".$zipName,$zipName,$headers);
        foreach($headers as $header){
            header($header);
        }
        readfile(Storage::path("resources_packages/zips")."/".basename($zipName));
        exit(0);
    }

}

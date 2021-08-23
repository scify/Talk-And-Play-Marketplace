<?php


namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use Illuminate\Support\Facades\Auth;

class ResourcesPackageManager extends ResourceManager {
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected ResourceRepository $resourceRepository;
    protected int $type_id;

    public function __construct(ResourceRepository           $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository   $resourcesPackageRepository,
                                int                          $type_id = -1) {
        $this->resourceRepository = $resourceRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        $this->type_id = $type_id;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository);
    }


    public function storeResourcePackage(Resource $resource, int $lang) {


        $this->resourcesPackageRepository->create(
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
        return $this->resourcesPackageRepository->getResourcesPackage($id)->first();
    }

    public function approveResourcesPackage($id) {
        return $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::APPROVED]
            , $id);
    }


    public function getContentLanguagesForResources() {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getResourcePackages(int $lang_id, int $type_id, int $status_id) {
        return $this->resourcesPackageRepository->allWhere([
            'type_id' => $type_id,
            'status_id' => $status_id,
            'lang_id' => $lang_id
        ], array('*'), 'id', 'asc', ['coverResource', 'coverResource.childrenResources', 'creator']);
    }


    public function downloadGamePackage($id, $package, $gameType = "") {
        $fileManager = new ResourceFileManager();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);
        $parentResource = $this->resourceRepository->find($id);

        #TODO Storage::put
        #TODO: create zips folder
        $tmpDir = sys_get_temp_dir() . '/' . 'package-' . $id;
        if (is_dir($tmpDir) == false) {
            mkdir($tmpDir, 0700);
        }

        $header =
            <<<XML
<?xml version='1.0'?>
<game name="" enabled="true" gameType="$gameType" languages="">
</game>
XML;
        $xmlTemplate = simplexml_load_string($header);
        $xmlTemplate['name'] = $parentResource->name;

        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;
        $xmlTemplate['languages'] = $lang;

        $image_name = basename($parentResource->img_path);
        $fileManager->copyResourceToDirectory($tmpDir, $image_name, "image");
        $xmlTemplate->addChild('image', $image_name);
        $xmlTemplate->addChild('difficulty', 0);#todo add difficulty
        $xmlTemplate->addChild('gameImages');
        $gameImages = $xmlTemplate->gameImages;

        foreach ($childrenResourceCards as $i => $child) {
            $image_name = basename($child->img_path);
            $fileManager->copyResourceToDirectory($tmpDir, $image_name, "image");
            $gameImage = $gameImages->addChild('gameImage', $image_name);
            $gameImage->addAttribute('enabled', "true");
            $gameImage->addAttribute('order', strval($i + 1));
        }

        $xmlTemplate->asXML($tmpDir . '/structure.xml');
        $zipName = basename($tmpDir) . ".zip";
        $fileManager->getCreateZip($zipName, $tmpDir);

        //then send the headers to force download the zip file
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$zipName");
        header("Pragma: no-cache");
        header("Expires: 0");
        #return Storage::download("zips/zip_300.zip");
        readfile($zipName);
        exit(0);
    }

}

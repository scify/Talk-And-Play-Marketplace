<?php


namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\ReportsRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;

class ResourcesPackageManager extends ResourceManager {
    public ResourcesPackageRepository $resourcesPackageRepository;
    protected ReportsRepository $reportsRepository;
    protected ResourceManager $resourceManager;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;
    protected int $type_id;

    public function __construct(ResourceRepository           $resourceRepository,
                                ContentLanguageLkpRepository $contentLanguageLkpRepository,
                                ResourcesPackageRepository   $resourcesPackageRepository,
                                ReportsRepository $reportsRepository,
                                int                          $type_id = -1) {
        $this->reportsRepository = $reportsRepository;
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
                'status_id' => strval(ResourceStatusesLkp::CREATED_PENDING_APPROVAL),
                'lang_id' => $lang,
                'creator_user_id' => Auth::id(),
                'admin_user_id' => null,
                'card_id' => $resource['id'],
                'downloadable' => true
            ]
        );
    }

    public function updateResourcePackage($request, $id){
        $package = $this->resourcesPackageRepository->getResourcesPackage($id);
        $coverCardId = $package->card_id;
        $package = $this->resourcesPackageRepository->update(["lang_id" => $request->lang], $id);
        $this->updateResource( $request , $coverCardId);

        return $package;

    }

    public function getResourcesPackage($id) {
        return $this->resourcesPackageRepository->getResourcesPackage($id);
    }


    public function getResourcesPackageWithCoverCard($id) {
        return $this->resourcesPackageRepository->getResourcesPackageWithCoverCard($id);
    }


    public function approveResourcesPackage($id) {
        return $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::APPROVED]
            , $id);
    }

    public function rejectResourcesPackage($id) {
        return $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::REJECTED]
            , $id);
    }

    public function submitResourcesPackage($id) {
        return $this->resourcesPackageRepository->update(
            ['status_id' => ResourceStatusesLkp::CREATED_PENDING_APPROVAL]
            , $id);
    }


    public function getContentLanguagesForResources() {
        return $this->contentLanguageLkpRepository->all();
    }


    public function getResourcesPackages(int $lang_id=null, $user_id = null, array $type_ids, array $status_ids ) {
        return $this->resourcesPackageRepository->getResourcesPackages($type_ids, $user_id, $lang_id, $status_ids);
    }


    public function getChildrenCardsWithParent($id){
        return $this->resourceRepository->getChildrenCardsWithParent($id);
    }

    public function downloadGamePackage($package, $gameType = "") {
        $fileManager = new ResourceFileManager();
        $this->resourcesPackageRepository->update(
            [
                "num_downloads" => $package->num_downloads+1
            ], $package->id
        );
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($package->card_id);
        $parentResource = $this->resourceRepository->find($package->card_id);
        $packageDir = 'resources_packages/zips/package-' . $package->id;
        if (is_dir($packageDir) == false) {
            Storage::makeDirectory($packageDir);
        }
        $header =
            <<<XML
<?xml version='1.0' encoding="UTF-8"?>
<game name="" enabled="true" gameType="$gameType">
</game>
XML;
        $xmlTemplate = simplexml_load_string($header);
        $xmlTemplate['name']  = str_replace(array("?","!",",",";"), "",  $parentResource->name );


//        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;
//        $xmlTemplate['languages'] = $lang;

        $imageName = basename($parentResource->img_path);
        $imageName = utf8_encode($imageName);
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
        foreach($headers as $h){
            header($h);
        }
        readfile(Storage::path("resources_packages/zips")."/".basename($zipName));
        exit(0);
    }



    public function destroyResourcesPackage($id) {
        $package = $this->resourcesPackageRepository->getResourcesPackage($id);
        $coverCardId = $package->card_id;
        $this->destroyResource($coverCardId);
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($coverCardId);
        foreach ($childrenResourceCards as $child){
            $this->destroyResource($child->id);
        }
       $this->resourcesPackageRepository->delete($id);
    }


    public function getPendingResourcePackages(){
        return  $this->resourcesPackageRepository->getPendingResourcePackages();
    }


    public function reportResourcesPackage($id, $reporting_user_id, $reportReason, $reportComment){
        $storeArr = [
            'package_id' => $id,
            'reporting_user_id' => $reporting_user_id,
            'reason' =>  $reportReason,
            'comment' => $reportComment,
        ];
        $reports = $this->reportsRepository->create($storeArr);
    }

    public function getReportedPackages(array $type_ids, int $lang_id = null){
        $reports =  $this->reportsRepository->all();
        $packagesWithReportInfo = Collection::empty();
        foreach($reports as $report){
            $package = $this->resourcesPackageRepository->getResourcesPackage($report->package_id);
            $package->reportData = $report;
            $package->creator = $report->creator;
            $coverCardId = $package->card_id;
            $package->cover_resource = $this->resourceRepository->find($coverCardId);
            $packagesWithReportInfo->push($package);
        }
        $packagesWithReportInfo= $packagesWithReportInfo->filter(
            function($x) use ($type_ids, $lang_id){
                return
                    in_array($x->type_id, $type_ids) && ( !$lang_id || $x->lang_id==$lang_id);
            }
        );
        $packagesWithReportInfo = array_values($packagesWithReportInfo->toArray());
        return $packagesWithReportInfo;
    }


}

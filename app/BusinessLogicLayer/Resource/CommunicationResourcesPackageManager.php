<?php

namespace App\BusinessLogicLayer\Resource;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\ReportsRepository;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourcesPackageRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\ViewModels\CreateEditResourceVM;
use App\ViewModels\DisplayPackageVM;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunicationResourcesPackageManager extends ResourcesPackageManager {
    public ResourcesPackageRepository $resourcesPackageRepository;

    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;

    protected ResourceRepository $resourceRepository;

    protected ReportsRepository $reportsRepository;

    const maximumCardsThreshold = 10;

    const type_id = ResourceTypesLkp::COMMUNICATION;

    public function __construct(ResourceRepository $resourceRepository, ContentLanguageLkpRepository $contentLanguageLkpRepository, ResourcesPackageRepository $resourcesPackageRepository, ReportsRepository $reportsRepository) {
        $this->resourceRepository = $resourceRepository;
        $this->reportsRepository = $reportsRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
        $this->resourcesPackageRepository = $resourcesPackageRepository;
        parent::__construct($resourceRepository, $contentLanguageLkpRepository, $resourcesPackageRepository, $reportsRepository, self::type_id);
    }

    public function getCreateResourcesPackageViewModel(): CreateEditResourceVM {
        $contentLanguages = $this->getContentLanguagesForResources();

        return new CreateEditResourceVM($contentLanguages,
            new Resource,
            new Collection,
            new ResourcesPackage,
            self::maximumCardsThreshold,
            self::type_id);
    }

    public function getEditResourceViewModel($id, $package): CreateEditResourceVM {
        $contentLanguages = $this->getContentLanguagesForResources();
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($id);

        return new CreateEditResourceVM($contentLanguages,
            $this->resourceRepository->find($id),
            $childrenResourceCards,
            $package,
            self::maximumCardsThreshold,
            ResourceTypesLkp::COMMUNICATION);
    }


    //    public function getApprovedCommunicationPackagesParentResources(): DisplayPackageVM {
    //        $approvedCommunicationPackages = $this->resourcesPackageRepository->getResourcesPackages([self::type_id],  Auth::id());
    //        return new DisplayPackageVM($approvedCommunicationPackages);
    //    }
    //

    public function downloadPackage($package) {
        $fileManager = new ResourceFileManager;
        $childrenResourceCards = $this->resourceRepository->getChildrenCardsWithParent($package->card_id);
        $parentResource = $this->resourceRepository->find($package->card_id);
        $packageDir = 'resources_packages/zips/package-' . $package->id;

        $this->resourcesPackageRepository->update(
            [
                'num_downloads' => $package->num_downloads + 1,
            ], $package->id
        );
        if (is_dir($packageDir) == false) {
            Storage::makeDirectory($packageDir);
        }
        $header =
            <<<'XML'
<?xml version='1.0' encoding="UTF-8"?>
<category name="" enabled="true" languages="">
</category>
XML;
        $xmlTemplate = simplexml_load_string($header);
        $xmlTemplate['name'] = str_replace(['?', '!', ',', ';'], '', $parentResource->name);
        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;

        $xmlTemplate['languages'] = $lang === 'el' ? 'gr' : $lang;

        //        $lang = $this->contentLanguageLkpRepository->find($package->lang_id)->code;
        //        $xmlTemplate['languages'] = $lang;

        $imageName = basename($parentResource->img_path);
        $audioName = basename($parentResource->audio_path);
        $dirPath = Storage::path($packageDir);
        $fileManager->copyResourceToDirectory($dirPath, $imageName, 'image');
        $fileManager->copyResourceToDirectory($dirPath, $audioName, 'audio');
        $xmlTemplate->addChild('image', $imageName);
        $xmlTemplate->addChild('sound', $audioName);
        $xmlTemplate->addChild('categories');
        $categories = $xmlTemplate->categories;

        foreach ($childrenResourceCards as $child) {
            $imageName = basename($child->img_path);
            $audioName = basename($child->audio_path);
            $fileManager->copyResourceToDirectory($dirPath, $imageName, 'image');
            $fileManager->copyResourceToDirectory($dirPath, $audioName, 'audio');
            $category = $categories->addChild('category');
            $category->addAttribute('enabled', 'true');
            $category->addAttribute('name', $child->name);
            $category->addChild('image', $imageName);
            $category->addChild('sound', $audioName);
        }

        $xmlTemplate->asXML($dirPath . '/structure.xml');
        $zipName = basename($dirPath . '.zip');
        $fileManager->getCreateZip($dirPath);
        Storage::makeDirectory('resources_packages/zips');
        Storage::deleteDirectory($packageDir);
        $headers = [
            'Content-type: application/zip',
            "Content-Disposition: attachment; filename={$zipName}",
            'Pragma: no-cache',
            'Expires: 0',
        ];
        //       return Storage::download("resources_packages/zips/".$zipName,$zipName,$headers);
        foreach ($headers as $header) {
            header($header);
        }
        readfile(Storage::path('resources_packages/zips') . '/' . basename($zipName));
        exit(0);
    }
}

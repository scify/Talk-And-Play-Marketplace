<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourcesPackageRatingManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourcePackageRatingController extends Controller {
    protected $resourcesPackageRatingManager;

    public function __construct(ResourcesPackageRatingManager $resourcesPackageRatingManager) {
        $this->resourcesPackageRatingManager = $resourcesPackageRatingManager;
    }

    public function getUserRatingForResourcesPackage(Request $request) {
        $this->validate($request, [
            'resources_package_id' => 'required|integer',
        ]);

        return $this->resourcesPackageRatingManager->getUserRatingForResourcesPackage(
            Auth::id(),
            $request->resources_package_id
        );
    }

    public function storeOrUpdateRating(Request $request) {
        $this->validate($request, [
            'resources_package_id' => 'required|integer|exists:resources_package,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // The route requires auth; the rating always belongs to the logged-in user.
        return $this->resourcesPackageRatingManager->storeOrUpdateRating(
            Auth::id(),
            $request->resources_package_id,
            $request->rating
        );
    }
}

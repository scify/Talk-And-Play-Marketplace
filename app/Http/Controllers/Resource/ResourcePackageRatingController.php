<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourcesPackageRatingManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ResourcePackageRatingController extends Controller {
    protected $resourcesPackageRatingManager;

    public function __construct(ResourcesPackageRatingManager $resourcesPackageRatingManager) {
        $this->resourcesPackageRatingManager = $resourcesPackageRatingManager;
    }

    public function getUserRatingForResourcesPackage(Request $request) {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'resources_package_id' => 'required|integer',
        ]);

        return $this->resourcesPackageRatingManager->getUserRatingForResourcesPackage(
            $request->user_id,
            $request->resources_package_id
        );
    }

    public function storeOrUpdateRating(Request $request) {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'resources_package_id' => 'required|integer|exists:resources_package,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if (!Auth::check()) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $this->resourcesPackageRatingManager->storeOrUpdateRating(
            $request->user_id,
            $request->resources_package_id,
            $request->rating
        );
    }
}

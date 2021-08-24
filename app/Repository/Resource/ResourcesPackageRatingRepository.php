<?php

namespace App\Repository\Resource;

use App\Models\Resource\ResourcesPackageRating;
use App\Repository\Repository;

class ResourcesPackageRatingRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return ResourcesPackageRating::class;
    }
}

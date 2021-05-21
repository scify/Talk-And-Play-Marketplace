<?php


namespace App\Repository\Resource;


use App\Models\Resource\ResourceStatusLkp;
use App\Repository\Repository;

class ResourceStatusLkpRepository extends Repository {

    function getModelClassName() {
        return ResourceStatusLkp::class;
    }
}

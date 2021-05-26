<?php


namespace App\Repository\Resource;


use App\Repository\Repository;

class ResourceRepository extends \App\Repository\Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return Repository::class;
    }
}

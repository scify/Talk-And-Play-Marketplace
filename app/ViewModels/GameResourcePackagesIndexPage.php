<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

class GameResourcePackagesIndexPage {

    public $resourceTypesLkp;

    public function __construct(Collection $resourceTypesLkp) {
        $this->resourceTypesLkp = $resourceTypesLkp;
    }

}

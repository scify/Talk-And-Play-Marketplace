<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

class DisplayPackageVM {
    public Collection $resourcesPackages;

    public function __construct(Collection $resourcesPackages) {
        $this->resourcesPackages = $resourcesPackages;
    }
}

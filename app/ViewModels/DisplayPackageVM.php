<?php


namespace App\ViewModels;
use Illuminate\Support\Collection;


class DisplayPackageVM
{

    public Collection $parentResources;

    public function __construct($parentResources)
    {
        $this->parentResources = $parentResources;
    }

}

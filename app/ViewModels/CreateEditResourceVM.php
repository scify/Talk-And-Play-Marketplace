<?php


namespace App\ViewModels;


use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use Illuminate\Collections\ItemNotFoundException;
use Illuminate\Support\Collection;
use PHPUnit\Exception;

class CreateEditResourceVM
{
    public Collection $languages;
    public Resource $resource;
    public Collection $childrenCards;
    public ResourcesPackage $package;

    public function __construct(Collection $languages, Resource $resource, Collection $childrenCards,ResourcesPackage $package, $maximumCardThreshold = 10)
    {
        $this->languages = $languages;
        $this->resource = $resource;
        $this->childrenCards = $childrenCards;
        $this->package = $package;
        $this->maximumCardThreshold = $maximumCardThreshold;

    }

    public function ReachedMaximumCardLimit()
    {
        $numCards = sizeof($this->childrenCards);
        return $numCards === $this->maximumCardThreshold;
    }

    public function isEditMode(): bool
    {
        return ($this->resource->id != null);
    }


}

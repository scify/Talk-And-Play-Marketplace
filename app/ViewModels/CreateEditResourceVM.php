<?php


namespace App\ViewModels;


use App\Models\Resource\Resource;
use App\Models\Resource\ResourcePack;
use Illuminate\Collections\ItemNotFoundException;
use Illuminate\Support\Collection;
use PHPUnit\Exception;

class CreateEditResourceVM
{
    public Collection $languages;
    public Resource $resource;
    public Collection $childrenCards;
    public ResourcePack $package;

    public function __construct(Collection $languages, Resource $resource, Collection $childrenCards,ResourcePack $package)
    {
        $this->languages = $languages;
        $this->resource = $resource;
        $this->childrenCards = $childrenCards;
        $this->package = $package;

    }

    private $maximumCardThreshold = 10;

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

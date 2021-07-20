<?php


namespace App\ViewModels;


use App\Models\Resource\Resource;
use Illuminate\Support\Collection;

class CreateEditResourceVM
{
    public Collection $languages;
    public Resource $resource;
    public Collection $childrenCards;
    public int $packageId;

    public function __construct(Collection $languages, Resource $resource, Collection $childrenCards, int $packageId)
    {
        $this->languages = $languages;
        $this->resource = $resource;
        $this->childrenCards = $childrenCards;
        $this->packageId = $packageId;
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

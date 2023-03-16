<?php

namespace App\ViewModels;

use App\Models\Resource\Resource;
use App\Models\Resource\ResourcesPackage;
use Illuminate\Support\Collection;

class CreateEditResourceVM {
    public Collection $languages;

    public Resource $resource;

    public Collection $childrenCards;

    public ResourcesPackage $package;

    public int $maximumCardThreshold;

    public int $type_id;

    public function __construct(Collection $languages,
                                Resource $resource,
                                Collection $childrenCards,
                                ResourcesPackage $package,
                                $maximumCardThreshold = 10,
                                int $type_id = -1) {
        $this->languages = $languages;
        $this->resource = $resource;
        $this->childrenCards = $childrenCards;
        $this->package = $package;
        $this->maximumCardThreshold = $maximumCardThreshold;
        $this->type_id = $type_id;
    }

    public function ReachedMaximumCardLimit(): bool {
        $numCards = count($this->childrenCards);

        return $numCards === $this->maximumCardThreshold;
    }

    public function isEditMode(): bool {
        return $this->resource->id != null;
    }
}

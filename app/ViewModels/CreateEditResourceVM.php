<?php


namespace App\ViewModels;


use App\Models\Resource\Resource;
use Illuminate\Support\Collection;

class CreateEditResourceVM{
    public Collection $languages;
    public Resource $resource;
    public Collection $childrenCards;
    public function __construct(Collection $languages, Resource $resource, Collection $childrenCards){
        $this->languages = $languages;
        $this->resource = $resource;
        $this->childrenCards = $childrenCards;
    }

    public function isEditMode(): bool{
        return ($this->resource->id != null);
    }


}

<?php


namespace App\ViewModels;


use App\Models\Resource\Resource;
use Illuminate\Support\Collection;

class CreateEditResourceVM{
    public Collection $languages;
    public Resource $resource;
    public function __construct(Collection $languages, Resource $resource){
        $this->languages = $languages;
        $this->resource = $resource;
    }

    public function isEditMode(): bool{
        return ($this->resource->id != null);
    }


}

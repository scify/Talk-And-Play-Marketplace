<?php


namespace App\ViewModels;


use Illuminate\Support\Collection;

class CreateEditResourceVM{
    public Collection $languages;
    public function __construct(Collection $languages){
        $this->languages=$languages;
    }


}

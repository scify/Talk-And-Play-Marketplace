<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

class UsersManagementPageVM {
    public $users;

    public function __construct(Collection $users) {
        $this->users = $users;
    }
}

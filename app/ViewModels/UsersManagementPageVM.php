<?php

namespace App\ViewModels;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;
use Illuminate\Support\Collection;

class UsersManagementPageVM {

    public Collection $users;
    protected UserRoleManager $userRoleManager;

    public function __construct(Collection $users, UserRoleManager $userRoleManager) {
        $this->users = $users;
        $this->userRoleManager = $userRoleManager;
    }

    public function userIsAdmin(User $user): bool {
        return $this->userRoleManager->userHasAdminRole($user);
    }
}

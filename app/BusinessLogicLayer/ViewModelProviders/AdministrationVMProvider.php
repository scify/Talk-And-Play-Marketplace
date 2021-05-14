<?php


namespace App\BusinessLogicLayer\ViewModelProviders;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Repository\Repository;
use App\Repository\User\UserRepository;
use App\ViewModels\UsersManagementPageVM;

class AdministrationVMProvider {
    private Repository $userRepository;
    protected UserRoleManager $userRoleManager;

    public function __construct(UserRepository $userRepository, UserRoleManager $userRoleManager) {
        $this->userRepository = $userRepository;
        $this->userRoleManager = $userRoleManager;
    }

    public function getUsersManagementVM(): UsersManagementPageVM {
        $users = $this->userRepository->all(array('*'), null, null, ['roles']);
        return new UsersManagementPageVM($users, $this->userRoleManager);
    }

}

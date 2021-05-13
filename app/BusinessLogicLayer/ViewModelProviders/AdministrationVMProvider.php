<?php


namespace App\BusinessLogicLayer\ViewModelProviders;

use App\Repository\User\UserRepository;
use App\ViewModels\UsersManagementPageVM;

class AdministrationVMProvider {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getUsersManagementVM(): UsersManagementPageVM {
        $users = $this->userRepository->all();
        return new UsersManagementPageVM($users);
    }

}

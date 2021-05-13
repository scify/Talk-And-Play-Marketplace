<?php


namespace App\Repository\User\UserRole;


use App\Models\UserRole\UserRole;
use App\Repository\Repository;

class UserRoleRepository extends Repository {

    function getModelClassName() {
        return UserRole::class;
    }

    public function getUserRoleWithTrashed($data) {
        return UserRole::where($data)->withTrashed()->first();
    }
}

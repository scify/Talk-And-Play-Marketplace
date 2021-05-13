<?php


namespace App\Repository\User\UserRole;


use App\Models\UserRole\UserRoleLkp;
use App\Repository\Repository;

class UserRoleLkpRepository extends Repository {

    function getModelClassName() {
        return UserRoleLkp::class;
    }
}

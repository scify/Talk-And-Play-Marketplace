<?php

namespace App\Repository\User\UserRole;


abstract class UserRolesLkp {
    //ATTENTION: these values match with the db values defined in database\seeds\UsersRoleLkpTableSeeder.php
    const ADMIN = 1;
    const CONTENT_CREATOR = 2;
}

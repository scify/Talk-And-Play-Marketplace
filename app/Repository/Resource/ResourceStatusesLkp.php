<?php

namespace App\Repository\Resource;

abstract class ResourceStatusesLkp {
    //ATTENTION: these values match with the db values defined in database\seeds\ResourceStatusLkpTableSeeder.php
    const CREATED_PENDING_APPROVAL = 1;
    const APPROVED = 2;
    const REJECTED = 3;
}

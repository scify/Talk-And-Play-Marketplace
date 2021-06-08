<?php

namespace App\Repository\Resource;


abstract class ResourceTypesLkp {
    //ATTENTION: these values match with the db values defined in database\seeds\ResourceTypeLkpTableSeeder.php
    const COMMUNICATION = 1;
    const GAME = 2;
}

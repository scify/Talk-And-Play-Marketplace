<?php

namespace App\Repository\Resource;

abstract class ResourceTypesLkp {
    //ATTENTION: these values match with the db values defined in database\seeds\ResourceTypeLkpTableSeeder.php
    const COMMUNICATION = 1;

    const RESPONSE_GAME = 2;

    const TIME_GAME = 3;

    const SIMILAR_GAME = 4;
}

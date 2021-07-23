<?php


namespace App\Repository;


use App\Models\GameCategoriesLkp;

class GameCategoriesLkpRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName(): string
    {
        return GameCategoriesLkp::class;
    }
}

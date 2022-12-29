<?php

namespace App\Repository;

use App\Models\GameCategoriesLkp;

class GameCategoriesLkpRepository extends Repository {
    /**
     * {@inheritDoc}
     */
    public function getModelClassName(): string {
        return GameCategoriesLkp::class;
    }
}

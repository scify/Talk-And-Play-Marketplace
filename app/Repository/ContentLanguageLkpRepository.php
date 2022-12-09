<?php

namespace App\Repository;

use App\Models\ContentLanguageLkp;

class ContentLanguageLkpRepository extends Repository {
    /**
     * {@inheritDoc}
     */
    public function getModelClassName() {
        return ContentLanguageLkp::class;
    }
}

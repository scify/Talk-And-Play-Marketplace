<?php

namespace App\Repository;

use App\Models\DesktopAppAnnouncementTranslation;

class DesktopAppAnnouncementTranslationRepository extends Repository {
    public function getModelClassName() {
        return DesktopAppAnnouncementTranslation::class;
    }
}

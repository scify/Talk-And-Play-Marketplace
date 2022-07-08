<?php

namespace App\Repository;

use App\Models\DesktopAppAnnouncementTranslation;

class DesktopAppAnnouncementTranslationRepository extends Repository {

    function getModelClassName() {
        return DesktopAppAnnouncementTranslation::class;
    }
}

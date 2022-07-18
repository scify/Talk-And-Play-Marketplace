<?php

namespace App\Repository;

use App\Models\DesktopAppAnnouncement;

class DesktopAppAnnouncementRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName() {
        return DesktopAppAnnouncement::class;
    }

    public function getLatest() {
        $result = DesktopAppAnnouncement::with(['translations', 'translations.language'])->orderBy("desktop_app_announcements.updated_at")->first();
        $toReturn = (object)[];
        if (!empty($result)){
            $toReturn->translations = [];
            $toReturn->severity = $result->severity;
            $toReturn->type = $result->type;
            $toReturn->updated_at = $result->updated_at;
            foreach ($result->translations as $translation) {
                $translationObj = (object)[];
                $translationObj->title = $translation->title;
                $translationObj->message = $translation->message;
                $translationObj->link = $translation->link;
                $translationObj->language = $translation->language->code;
                $toReturn->translations[] = $translationObj;
            }
        }
        return $toReturn;
    }
}

<?php

namespace App\Repository;

use App\Models\DesktopAppAnnouncement;

class DesktopAppAnnouncementRepository extends Repository {
    /**
     * {@inheritDoc}
     */
    public function getModelClassName() {
        return DesktopAppAnnouncement::class;
    }

    public function getAnnouncementsForVersion($version = null) {
        $queryBuilder = DesktopAppAnnouncement::with(['translations', 'translations.language'])
            ->where('status', 1);

        if ($version) {
            $queryBuilder->where('min_version', '<=', $version)
                ->where('max_version', '>=', $version);
        }
        $queryBuilder->orderBy('desktop_app_announcements.updated_at', 'asc');
        $results = $queryBuilder->get();
        $announcements = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $announcement = (object) [];
                $announcement->translations = [];
                $announcement->severity = $result->severity;
                $announcement->type = $result->type;
                $announcement->min_version = $result->min_version;
                $announcement->max_version = $result->max_version;
                $announcement->updated_at = $result->updated_at;
                foreach ($result->translations as $translation) {
                    $translationObj = (object) [];
                    $translationObj->title = $translation->title;
                    $translationObj->message = $translation->message;
                    $translationObj->link = $translation->link;
                    $translationObj->language = $translation->language->code;
                    $announcement->translations[] = $translationObj;
                }
                $announcements[] = $announcement;
            }
        }

        return $announcements;
    }

    public function getLatest($version = null) {
        $queryBuilder = DesktopAppAnnouncement::with(['translations', 'translations.language'])
            ->where('status', 1);

        if ($version) {
            $queryBuilder->where('min_version', '<=', $version)
                ->where('max_version', '>=', $version);
        }
        $result = $queryBuilder->first();
        $toReturn = (object) [];
        if (!empty($result)) {
            $toReturn->translations = [];
            $toReturn->severity = $result->severity;
            $toReturn->type = $result->type;
            $toReturn->min_version = $result->min_version;
            $toReturn->max_version = $result->max_version;
            $toReturn->updated_at = $result->updated_at;
            foreach ($result->translations as $translation) {
                $translationObj = (object) [];
                $translationObj->title = $translation->title;
                $translationObj->message = $translation->message;
                $translationObj->link = $translation->link;
                $translationObj->language = $translation->language->code;
                $toReturn->translations[] = $translationObj;
            }
        }

        return $toReturn;
    }

    public function activate($id) {
        // deactivate previous active announcements
        //        $prevActiveAnnouncement = DesktopAppAnnouncement::with(['translations', 'translations.language'])->where('status',1)->first();
        //        if(!empty($prevActiveAnnouncement)){
        //            $prevActiveAnnouncement->update(['status' => 0]);
        //        }

        // activate new announcement
        $announcementToActivate = DesktopAppAnnouncement::with(['translations', 'translations.language'])->find($id);
        $announcementToActivate->update(['status' => 1]);
    }

    public function deactivate($id) {
        $announcementToDeactivate = DesktopAppAnnouncement::with(['translations', 'translations.language'])->find($id);
        $announcementToDeactivate->update(['status' => 0]);
    }
}

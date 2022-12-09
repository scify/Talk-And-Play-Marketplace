<?php

namespace App\Repository\Analytics;

use App\Models\Analytics\AnalyticsEvent;
use App\Repository\Repository;

class AnalyticsEventRepository extends Repository {
    /**
     * {@inheritDoc}
     */
    public function getModelClassName() {
        return AnalyticsEvent::class;
    }
}

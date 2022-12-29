<?php

namespace App\Repository;

use App\Models\Reports;

class ReportsRepository extends Repository {
    protected array $defaultRelationships = ['creator'];

    public function getModelClassName() {
        return Reports::class;
    }

    public function getReports() {
        $whereArray = [];
        $whereArray['deleted_at'] = null;

        return Reports::where($whereArray)->with($this->defaultRelationships);
    }
}

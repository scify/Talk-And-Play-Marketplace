<?php


namespace App\Repository;
use App\Models\Reports;
use App\Repository\Repository;
use App\Repository\User\UserRole\UserRolesLkp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReportsRepository extends Repository {
    protected array $defaultRelationships = ['creator'];

    function getModelClassName() {
        return Reports::class;
    }


    function getReports() {
        $whereArray = [];
        $whereArray['deleted_at'] = null;
        return Reports::where($whereArray)->with($this->defaultRelationships);
    }


}

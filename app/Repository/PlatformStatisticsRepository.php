<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlatformStatisticsRepository {
    public function getPlatformStatistics(): Collection {
        return collect(DB::select('
            with users_stats as (
                select count(users.id) as "Total number of users"
                from users
                where
                users.deleted_at is null
            ),
            res_stats as (
                select count(res.id) as "Total number of cards "
                from resources as res
                where
                res.deleted_at is null
            ),
            res_packages_stats as (
                select count(res_package.id) as "Total number of card packages"
                from resources_package as res_package
                where
                res_package.deleted_at is null
            ),
            published_res_stats as (
                select count(res_package.id) as "Total number of published card packages"
                from resources_package as res_package
                where
                res_package.deleted_at is null and
                res_package.status_id = 2
            ),
            reports_stats as (
                select count(rep.id) as "Total number of reports"
                from reports as rep
                where
                rep.deleted_at is null
            ),
            ratings_stats as (
                select count(rat.id) as  "Total number of ratings"
                from resources_package_ratings as rat
            )
            select * from users_stats, res_stats, published_res_stats, reports_stats, ratings_stats;
        '));
    }

    public function getResourcesPackagesPerTypeStatistics(): Collection {
        return collect(DB::select('
            select count(resources_package.id) as num, rstl.name from resources_package
            inner join resource_types_lkp rstl on rstl.id = resources_package.type_id

            where resources_package.deleted_at is null
            and resources_package.status_id = 2
            group by type_id
        '))->flatten();
    }

    public function getNumOfResourcesPerUser(): Collection {
        return collect(DB::select('
            select concat(users.name, " (", users.email, ")" ) as user_name, count(res.id) as resources_num
            from users
            inner join resources res on res.creator_user_id = users.id

            where users.deleted_at is null
            and res.deleted_at is null

            group by users.id, users.name
            limit 10
        '));
    }

    public function getNumOfResourcesPackagesPerUser(): Collection {
        return collect(DB::select('
            select concat(users.name, " (", users.email, ")" ) as user_name, count(resp.id) as resources_packages_num
            from users
            inner join resources_package resp on resp.creator_user_id = users.id

            where users.deleted_at is null
            and resp.deleted_at is null
            and resp.status_id = 2
            group by users.id, users.name
            order by resources_packages_num desc
            limit 10
        '));
    }
}

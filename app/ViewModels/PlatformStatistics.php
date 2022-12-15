<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

class PlatformStatistics {

    public Collection $generalPlatformStatistics;
    public Collection $resourcePackagesPerTypeStatistics;
    public Collection $resourcesPerUserStatistics;
    public Collection $resourcesPackagesPerUserStatistics;

    /**
     * @param Collection $generalPlatformStatistics
     * @param Collection $resourcePackagesPerTypeStatistics
     * @param Collection $resourcesPerUserStatistics
     * @param Collection $resourcesPackagesPerUserStatistics
     */
    public function __construct(Collection $generalPlatformStatistics,
                                Collection $resourcePackagesPerTypeStatistics,
                                Collection $resourcesPerUserStatistics,
                                Collection $resourcesPackagesPerUserStatistics) {
        $this->generalPlatformStatistics = $generalPlatformStatistics;
        $this->resourcePackagesPerTypeStatistics = $resourcePackagesPerTypeStatistics;
        $this->resourcesPerUserStatistics = $resourcesPerUserStatistics;
        $this->resourcesPackagesPerUserStatistics = $resourcesPackagesPerUserStatistics;
    }


}

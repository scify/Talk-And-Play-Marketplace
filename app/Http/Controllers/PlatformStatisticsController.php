<?php

namespace App\Http\Controllers;

use App\Repository\PlatformStatisticsRepository;
use App\ViewModels\PlatformStatistics;
use Illuminate\Contracts\View\View;

class PlatformStatisticsController extends Controller {
    protected PlatformStatisticsRepository $platformStatisticsRepository;

    /**
     * @param  PlatformStatisticsRepository  $platformStatisticsRepository
     */
    public function __construct(PlatformStatisticsRepository $platformStatisticsRepository) {
        $this->platformStatisticsRepository = $platformStatisticsRepository;
    }

    public function show_platform_statistics(): View {
        $viewModel = new PlatformStatistics(
            $this->platformStatisticsRepository->getPlatformStatistics(),
            $this->platformStatisticsRepository->getResourcesPackagesPerTypeStatistics(),
            $this->platformStatisticsRepository->getNumOfResourcesPerUser(),
            $this->platformStatisticsRepository->getNumOfResourcesPackagesPerUser()
        );

        return view('admin.platform-statistics', compact('viewModel'));
    }
}

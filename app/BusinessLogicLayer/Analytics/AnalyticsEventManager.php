<?php

namespace App\BusinessLogicLayer\Analytics;

use App\Models\User;
use App\Repository\Analytics\AnalyticsEventRepository;
use Exception;

class AnalyticsEventManager {
    protected AnalyticsEventRepository $analyticsEventRepository;

    public function __construct(AnalyticsEventRepository $analyticsEventRepository) {
        $this->analyticsEventRepository = $analyticsEventRepository;
    }

    /**
     * @throws Exception
     */
    public function storeUsageData(array $data) {
        $response = json_encode([]);

        return $this->analyticsEventRepository->create([
            'name' => $data['action'],
            'source' => $data['source'],
            'payload' => json_encode($data),
            'response' => $response,
        ]);
    }

    public function storeMarketplaceUsageData(User $user, array $data) {
        $data['devId'] = 'talk_and_play_marketplace';
        $data['source'] = 'web';
        $data['lang'] = app()->getLocale();
        $data['endpoint'] = 'marketplace';
        $data['version'] = config('app.version');

        return $this->storeUsageData($data);
    }
}

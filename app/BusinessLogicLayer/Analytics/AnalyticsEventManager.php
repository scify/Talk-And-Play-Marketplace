<?php

namespace App\BusinessLogicLayer\Analytics;

use App\BusinessLogicLayer\Shapes\ShapesIntegrationManager;
use App\Models\User;
use App\Repository\Analytics\AnalyticsEventRepository;
use Exception;

class AnalyticsEventManager {

    protected $analyticsEventRepository;
    protected $shapesIntegrationManager;

    public function __construct(AnalyticsEventRepository $analyticsEventRepository,
                                ShapesIntegrationManager $shapesIntegrationManager) {
        $this->analyticsEventRepository = $analyticsEventRepository;
        $this->shapesIntegrationManager = $shapesIntegrationManager;
    }

    /**
     * @throws Exception
     */
    public function storeUsageData(array $data) {
        $record = $this->analyticsEventRepository->create([
            'name' => $data['action'],
            'source' => 'web',
            'payload' => json_encode($data)
        ]);
        if (isset($data['token']) && strlen($data['token']) > 5 && ShapesIntegrationManager::isEnabled())
            $this->shapesIntegrationManager->sendUsageDataToDatalakeAPI($data);
        return $record;
    }

    public function storeMarketplaceUsageData(User $user, array $data) {
        $data['devId'] = 'talk_and_play_marketplace';
        $data['source'] = 'web';
        $data['lang'] = app()->getLocale();
        $data['endpoint'] = 'marketplace';
        $data['version'] = config('app.version');
        if ($user->shapes_auth_token)
            $data['token'] = $user->shapes_auth_token;
        return $this->storeUsageData($data);
    }

}

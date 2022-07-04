<?php

namespace App\BusinessLogicLayer\Analytics;

use App\BusinessLogicLayer\Shapes\ShapesIntegrationManager;
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
    public function sendUsageDataToDatalakeAPI(array $data) {
        $record = $this->analyticsEventRepository->create([
            'name' => $data['action'],
            'source' => 'web',
            'payload' => json_encode($data)
        ]);
        if (isset($data['token']) && strlen($data['token']) > 5 && ShapesIntegrationManager::isEnabled())
            $this->shapesIntegrationManager->sendUsageDataToDatalakeAPI($data);
        return $record;
    }

}

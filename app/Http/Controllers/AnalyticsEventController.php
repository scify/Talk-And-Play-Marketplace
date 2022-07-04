<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\Shapes\ShapesIntegrationManager;
use App\Repository\Analytics\AnalyticsEventRepository;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller {

    protected $analyticsEventRepository;
    protected $shapesIntegrationManager;

    public function __construct(AnalyticsEventRepository $analyticsEventRepository,
                                ShapesIntegrationManager $shapesIntegrationManager) {
        $this->analyticsEventRepository = $analyticsEventRepository;
        $this->shapesIntegrationManager = $shapesIntegrationManager;
    }

    public function store(Request $request) {
        $request->validate([
            'action' => 'required'
        ]);
        $data = $request->all();
        $record = $this->analyticsEventRepository->create([
            'name' => $request->action,
            'source' => $request->source,
            'payload' => json_encode($request->all())
        ]);
        if (isset($data['token']) && strlen($data['token']) > 5 && ShapesIntegrationManager::isEnabled())
            $this->shapesIntegrationManager->sendDesktopUsageDataToDatalakeAPI($data);
        return $record;
    }
}

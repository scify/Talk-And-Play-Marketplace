<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\Analytics\AnalyticsEventManager;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller {
    protected $analyticsEventManager;

    public function __construct(AnalyticsEventManager $analyticsEventManager) {
        $this->analyticsEventManager = $analyticsEventManager;
    }

    public function store(Request $request) {
        $request->validate([
            'action' => 'required',
            'lang' => 'required',
            'version' => 'required',
        ]);
        $data = $request->all();
        $data['source'] = 'desktop';
        $data['devId'] = 'talk_and_play_desktop';
        $data['endpoint'] = 'desktop';

        return $this->analyticsEventManager->storeUsageData($data);
    }
}

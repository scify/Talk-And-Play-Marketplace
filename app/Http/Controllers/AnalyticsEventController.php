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
            'version' => 'required'
        ]);
        return $this->analyticsEventManager->sendUsageDataToDatalakeAPI($request->all());
    }
}

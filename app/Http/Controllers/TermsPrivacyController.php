<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TermsPrivacyController extends Controller {
    public function __construct() {
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function showPrivacyPolicyPage(): View {
        $locale = app()->getLocale();

        return view('privacy-policy.' . $locale);
    }
}

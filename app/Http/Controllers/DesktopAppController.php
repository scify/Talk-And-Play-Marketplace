<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class DesktopAppController extends Controller {

    public function getOptionsForDesktopApp(): JsonResponse {
        return response()->json([
            'shapes_auth_url_login' => 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/login',
            'shapes_auth_url_register' => 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/register',
            'shapes_x_key' => config('app.shapes_key'),
            'sentry_dsn' => config('app.sentry_desktop_app_dsn'),
            'firebase_url' => config('app.firebase_desktop_app_url')
        ]);
    }

}
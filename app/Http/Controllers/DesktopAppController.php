<?php

namespace App\Http\Controllers;

use App\Repository\DesktopAppAnnouncementRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesktopAppController extends Controller {
    protected DesktopAppAnnouncementRepository $desktopAppAnnouncementRepository;

    /**
     * @param  DesktopAppAnnouncementRepository  $desktopAppAnnouncementRepository
     */
    public function __construct(DesktopAppAnnouncementRepository $desktopAppAnnouncementRepository) {
        $this->desktopAppAnnouncementRepository = $desktopAppAnnouncementRepository;
    }

    public function getOptionsForDesktopApp(Request $request): JsonResponse {
        $version = $request->input('version', null);
        $announcements = $this->desktopAppAnnouncementRepository->getAnnouncementsForVersion($version);
        $returnArray = [
            'shapes_auth_url_login' => 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/login',
            'shapes_auth_url_register' => 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/register',
            'shapes_x_key' => null,
            'sentry_dsn' => config('app.sentry_desktop_app_dsn'),
            'firebase_url' => config('app.firebase_desktop_app_url'), ];
        $returnArray['announcements'] = $announcements;

        return response()->json($returnArray);
    }
}

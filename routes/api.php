<?php

use App\Http\Controllers\AnalyticsEventController;
use App\Http\Controllers\DesktopAppController;
use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Http\Controllers\Resource\GameResourceController;
use App\Http\Controllers\Resource\ResourceController;
use App\Http\Controllers\Resource\ResourcePackageRatingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['throttle:api'])->group(function () {
    Route::get('/content-languages', [ResourceController::class, 'getContentLanguages'])->name('content_languages.get');
    Route::get('/communication-resources-packages', [CommunicationResourceController::class, 'getCommunicationResourcePackages'])->name('communication_resources.get');
    Route::get('/game-resources-packages/', [GameResourceController::class, 'getGameResourcePackages'])->name('game_resources.get');
    Route::get('/resources/user-reports', [ResourceController::class, 'getReports'])->name('resources.user-reports.get');
    Route::post('/resources/respond', [ResourceController::class, 'respond'])->name('resources.respond.post');
    Route::get('/resources/user-reports', [ResourceController::class, 'getReports'])->name('resources.user-reports.get');
    Route::get('/desktop-app/options/{version?}', [DesktopAppController::class, 'getOptionsForDesktopApp'])->name('desktop-app.options.get');
    Route::post('/analytics/store', [AnalyticsEventController::class, 'store']);
});

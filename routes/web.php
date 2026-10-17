<?php

use App\Http\Controllers\DesktopAppAnnouncementController;
use App\Http\Controllers\PlatformStatisticsController;
use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Http\Controllers\Resource\GameResourceController;
use App\Http\Controllers\Resource\ResourceController;
use App\Http\Controllers\Resource\ResourcePackageRatingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')->name('home');
Route::view('/how-it-works-marketplace', 'how_it_works_marketplace')->name('how-it-works-marketplace');
Route::view('/how-it-works-desktop', 'how_it_works_desktop')->name('how-it-works-desktop');
Route::view('/content-guidelines', 'content-guidelines')->name('content-guidelines');

$regexForLocalParameter = config('app.regex_for_validating_locale_at_routes');
$localeInfo = ['prefix' => '{lang}',
    'where' => ['lang' => $regexForLocalParameter],
    'middleware' => 'set.locale',
];

Route::group(['middleware' => 'auth'], function () {
    Route::get('/resources-package/user-rating', [ResourcePackageRatingController::class, 'getUserRatingForResourcesPackage'])->name('resources-package.user-rating.get');
    Route::post('/resources-package/user-rating', [ResourcePackageRatingController::class, 'storeOrUpdateRating'])->name('resources-package.user-rating.post');
});

Route::get('/lang/{lang}', [UserController::class, 'setLangLocaleCookie'])->name('set-lang-locale');
Route::get('/communication-cards', [CommunicationResourceController::class, 'index'])->name('communication_resources.index');
Route::get('/game-cards', [GameResourceController::class, 'index'])->name('game_resources.index');

// Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function () {
    Route::prefix('administration')->middleware('can:manage-platform')->name('administration.')->group(function () {
        Route::resource('users', UserController::class)->except([
            'create', 'edit', 'show',
        ]);

        Route::resource('desktop_app_announcements', DesktopAppAnnouncementController::class)->except([
            'create', 'edit', 'show',
        ]);

        Route::put('/desktop_app_announcements/activate/{id}', [DesktopAppAnnouncementController::class, 'activate'])->name('desktop_app_announcements.activate');
        Route::put('/desktop_app_announcements/deactivate/{id}', [DesktopAppAnnouncementController::class, 'deactivate'])->name('desktop_app_announcements.deactivate');
        Route::get('/platform-statistics', [PlatformStatisticsController::class, 'show_platform_statistics'])->name('platform_statistics');
    });

    Route::get('resources/approve_pending_packages', [ResourceController::class, 'approve_pending_packages'])->middleware('can:manage-platform')->name('resources_packages.approve_pending_packages');


    Route::resource('communication-resources', CommunicationResourceController::class)
        ->only([
            'create', 'store', 'edit',
        ])
        ->names([
            'create' => 'communication_resources.create',
            'store' => 'communication_resources.store',
            'edit' => 'communication_resources.edit',
            'download_package' => 'communication_resources.download_package',
        ]);


    Route::get('resources/clone_package/{id}', [ResourceController::class, 'clone_package'])->name('resources_packages.clone_package');
    Route::get('/my-packages', [ResourceController::class, 'my_packages'])->name('resources_packages.my_packages');
    Route::delete('/resources/delete/package/{id}', [ResourceController::class, 'delete_package'])->name('resources_packages.destroy_package');

    Route::get('/communication-cards/download/package/{id}', [CommunicationResourceController::class, 'download_package'])->name('communication_resources.download_package');

    Route::get('/resources/reported-packages', [ResourceController::class, 'reported_packages'])->middleware('can:manage-platform')->name('resources_packages.reported-packages');

    Route::post('/resources/approve/{id}', [ResourceController::class, 'approve'])->middleware('can:manage-platform')->name('resources.approve');
    Route::post('/resources/reject/{id}', [ResourceController::class, 'reject'])->middleware('can:manage-platform')->name('resources.reject');
    Route::put('/resources/submit/{id}', [ResourceController::class, 'submit'])->name('resources.submit');
    Route::post('/resources/report/{id}', [ResourceController::class, 'report'])->name('resources.report');
    Route::get('/resources/user-reports', [ResourceController::class, 'getReports'])->middleware('can:manage-platform')->name('resources.user-reports.get');
    Route::post('/resources/respond/{report_id}', [ResourceController::class, 'respond'])->middleware('can:manage-platform')->name('resources.respond.post');

    Route::resource('resources', ResourceController::class)
        ->except([
            'index', 'show', 'create', 'edit',
        ])
        ->names([
            'store' => 'resources.store',
            'destroy' => 'resources.destroy',
        ]);

    Route::put('/resources/update_resource_package/{id}', [ResourceController::class, 'update_resource_package'])->name('resources.update_resource_package');
    Route::put('/users/update/{user}', [UserController::class, 'updateProfile'])->name('users.update');

    Route::resource('game-cards', GameResourceController::class)
        ->only([
            'create', 'edit', 'update',
        ])
        ->names([
            'create' => 'game_resources.create',
            'edit' => 'game_resources.edit',
            'update' => 'game_resources.update',
            'download_package' => 'game_resources.download_package',
        ]);


    Route::get('/game-cards/download/package/{id}', [GameResourceController::class, 'download_package'])->name('game_resources.download_package');
});

Route::get('js/translations.js', function () {
    $lang = config('app.locale');
    $strings = [];

    foreach (['messages', 'validation'] as $name) {
        $strings[$name] = require app()->langPath() . '/' . $lang . '/' . $name . '.php';
    }

    return response('window.i18n = ' . json_encode($strings) . ';')
        ->header('Content-Type', 'text/javascript');
})->name('translations');

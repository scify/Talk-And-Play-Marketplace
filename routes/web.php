<?php

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Http\Controllers\Resource\GameResourceController;
use App\Http\Controllers\Resource\ResourceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
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
Route::view('/how-it-works', 'how_it_works')->name('how-it-works');
Route::get('/lang/{lang}', [UserController::class, 'setLangLocaleCookie'])->name('set-lang-locale');
Route::get('/communication-cards', [CommunicationResourceController::class, 'index'])->name('communication_resources.index');
Route::get('/game-cards', [GameResourceController::class, 'index'])->name('game_resources.index');
//TODO new route for resources with only methods ->only([]) without aliases

#Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function () {
    Route::prefix('administration')->middleware("can:manage-platform")->name('administration.')->group(function () {
        Route::resource('users', UserController::class)->except([
            'create', 'edit', 'show'
        ]);
    });



    Route::resource('communication-resources', CommunicationResourceController::class)
        ->except([//ONLY
            'index', 'show'
        ])
        ->names([
            'create' => 'communication_resources.create',
            'store' => 'communication_resources.store',
            'edit' => 'communication_resources.edit',
            'download_package' => 'communication_resources.download_package'
        ]);



    Route::get("resources/clone_package/{id}", [ResourceController::class, 'clone_package'])->name('resources_packages.clone_package');
    Route::get("/my-packages", [ResourceController::class, 'my_packages'])->name('resources_packages.my_packages');
    Route::get("/resources/delete/package/{id}", [ResourceController::class, 'delete_package'])->name('resources_packages.destroy_package');

    Route::get("/communication-cards/download/package/{id}", [CommunicationResourceController::class, 'download_package'])->name('communication_resources.download_package');


    Route::put("/resources/approve/{id}", [ResourceController::class, 'submit'])->name('resources.approve');
    Route::resource('resources', ResourceController::class)
        ->except([
            'index', 'show', 'create', 'edit'
        ])
        ->names([
            'store' => 'resources.store',
//            'update' => 'resources.update',
            'destroy' => 'resources.destroy'
        ]);
    Route::put("/resources/update_resource/{id}/{type_id}", [ResourceController::class, 'update_resource'])->name('resources.update_resource');


    Route::resource('game-cards', GameResourceController::class)
        ->except([
            'index', 'show'
        ])
        ->names([
            'create' => 'game_resources.create',
            'store' => 'game_resources.store',
            'edit' => 'game_resources.edit',
            'update' => 'game_resources.update',
//            'show_packages' => 'game_resources.show_packages',
            'show_package' => 'game_resources.show_package',
            'download_package' => 'game_resources.download_package'
        ]);

//    Route::get("/game-cards/show/packages/{type_id}", [GameResourceController::class, 'show_packages'])->name('game_resources.my_packages');

    Route::get("/game-cards/show/package/{id}", [GameResourceController::class, 'show_package'])->name('game_resources.show_package');
    Route::get("/game-cards/download/package/{id}", [GameResourceController::class, 'download_package'])->name('game_resources.download_package');


});

Route::get('js/translations.js', function () {
    $lang = config('app.locale');
    Cache::flush();
    $strings = Cache::rememberForever('lang_' . $lang . '.js', function () use ($lang) {
        $files = [
            resource_path('lang/' . $lang . '/messages.php'),
            resource_path('lang/' . $lang . '/validation.php'),
        ];
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });
    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('translations');

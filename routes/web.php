<?php

use App\Http\Controllers\Resource\CommunicationResourceController;
use App\Http\Controllers\Resource\GameResourceController;
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
Route::get('/lang/{lang}', [UserController::class, 'setLangLocaleCookie'])->name('set-lang-locale');
Route::get('/communication-cards', [CommunicationResourceController::class, 'index'])->name('communication_resources.index');
Route::get('/game-cards', [GameResourceController::class, 'index'])->name('game_resources.index');

Route::middleware(['auth'])->group(function () {
    Route::prefix('administration')->middleware("can:manage-platform")->name('administration.')->group(function () {
        Route::resource('users', UserController::class)->except([
            'create', 'edit', 'show'
        ]);
    });
    Route::resource('communication-cards', CommunicationResourceController::class)
        ->except([
            'index', 'show'
        ])
        ->names([
            'create' => 'communication_resources.create',
            'store' => 'communication_resources.store',
            'edit' => 'communication_resources.edit',
            'update' => 'communication_resources.update',
            'destroy' => 'communication_resources.destroy'
        ]);
    Route::put("/communication-cards/approve/{id}", [CommunicationResourceController::class, 'submit'])->name('communication_resources.approve');

    Route::resource('game-cards', GameResourceController::class)
        ->except([
            'index', 'show'
        ])
        ->names([
            'create' => 'game_resources.create',
            'store' => 'game_resources.store',
            'edit' => 'game_resources.edit',
            'update' => 'game_resources.update',
            'destroy' => 'game_resources.destroy'
        ]);
    Route::get('/game-cards/game_creation/{id}', [GameResourceController::class, 'game_creation'])->name('game_resources.game_creation');
    Route::put('/game-cards/edit_game/{id}', [GameResourceController::class, 'edit_game'])->name('game_resources.edit_game');
    Route::post('/game-cards/store_game/{id}', [GameResourceController::class, 'store_game'])->name('game_resources.store_game');

});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get("/content-languages", [CommunicationResourceController::class, 'getContentLanguages'])->name('content_languages.get');
    Route::get("/communication-resources", [CommunicationResourceController::class, 'getCommunicationResourcesForLanguage'])->name('communication_resources.for_language');

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

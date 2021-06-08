<?php

use App\Http\Controllers\Resource\CommunicationResourceController;
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

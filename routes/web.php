<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\Documentation\LayoutBuilderController;
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Address\AddressController;
use \App\Http\Controllers\Charts\ChartController;
use App\Http\Controllers\User\UserController;



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



// Route::get('/', function () {
//     return redirect('index');
// });

$menu = theme()->getMenu();
array_walk($menu, function ($val) {
    if (isset($val['path'])) {
        $route = Route::get($val['path'], [PagesController::class, 'index']);

        // Exclude documentation from auth middleware
        if (!Str::contains($val['path'], 'documentation')) {
            $route->middleware('auth');
        }

        // Custom page demo for 500 server error
        if (Str::contains($val['path'], 'error-500')) {
            Route::get($val['path'], function () {
                abort(500, 'Something went wrong! Please try again later.');
            });
        }
    }
});


// Documentations pages
Route::prefix('documentation')->group(function () {
    Route::get('getting-started/references', [ReferencesController::class, 'index']);
    Route::get('getting-started/changelog', [PagesController::class, 'index']);
    Route::resource('layout-builder', LayoutBuilderController::class)->only(['store']);
});


Route::group(['prefix' => 'public'], function () {
    Route::get('/home', [\App\Http\Controllers\Home\HomeController::class, 'index'])->name('index');
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/edit/{userId}', [UserController::class, 'profile'])->name('profile.edit');
        Route::post('/edit/{userId}', [UserController::class, 'editProfile'])->name('profile.update');
        Route::post('/getPerson', [UserController::class, 'getPerson'])->name('profile.get.person');
        Route::get('/getPicture/{idUser}', [UserController::class, 'getPicture'])->name('profile.get.picture');
        // Route::delete('/', [UserController::class, 'destroy'])->name('profile.destroy');
    });

    Route::group(['prefix' => 'address'], function () {
        Route::get('/getStates', [AddressController::class, 'getStates'])->name('address.findState');
        Route::get('/getCities', [AddressController::class, 'getCities'])->name('address.findCity');
        Route::get('/getNeighborhood', [AddressController::class, 'getNeighborhood'])->name('address.findNeighborhood');
        Route::get('/getCnaes', [AddressController::class, 'getCnaes'])->name('address.getCnaes');
    });

    Route::group(['prefix' => 'home'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });

    //Charts
    Route::group(['prefix' => 'charts'], function () {
        Route::get('/', [ChartController::class, 'index'])->name('charts.index');
        Route::get('/data-legal-nature', [ChartController::class, 'queryLegalNature'])->name('charts.legal_nature');
        Route::get('/data-economy-activity', [ChartController::class, 'queryEconomyActivity'])->name('charts.economy_activity');
    });

    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
        Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
        Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });

});

Route::resource('users', UsersController::class);

Route::get('posts', fn (\Illuminate\Http\Request $request) => 'data')->name('posts.index');

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__.'/auth.php';

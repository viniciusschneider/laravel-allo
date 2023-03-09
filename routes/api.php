<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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

Route::group([
    'middleware' => ['api', 'jwt'],
], function () {
    Route::group([
        'prefix' => 'auth',
        'controller' => AuthController::class,
    ], function () {
        Route::withoutMiddleware('jwt')
            ->group(function () {
                Route::post('register', 'register');
                Route::post('login', 'login');
            });

        Route::post('logout', 'logout');
        Route::get('refresh', 'refresh');
        Route::get('me', 'me');
    });

    Route::group([
        'prefix' => 'tasks',
        'controller' => TaskController::class,
        'as' => 'tasks.'
    ], function () {
        Route::post('create', 'create')->name('create');
        Route::get('list', 'list')->name('list');
        Route::get('{id}/details', 'details')->name('details');
        Route::put('{id}/update', 'update')->name('update');
        Route::delete('{id}/delete', 'delete')->name('delete');
    });
});

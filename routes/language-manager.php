<?php

use Backpack\LanguageManager\Http\Controllers\LanguageManagerCrudController;

/*
|--------------------------------------------------------------------------
| Backpack\LanguageManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\LanguageManager package.
|
*/
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
], function () {
    Route::crud('language-manager', LanguageManagerCrudController::class);
});

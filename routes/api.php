<?php

use Illuminate\Support\Facades\Route;
use AllatraIt\LaravelOpenapi\Controllers\ApiOpenapiController;

Route::prefix('api')->middleware('api')->group(static function () {
    Route::get('/doc', [ApiOpenapiController::class, 'index'])->name('laravel-openapi-api-doc');
});

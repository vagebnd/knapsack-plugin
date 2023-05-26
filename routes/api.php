<?php

use Knapsack\Compass\Support\Facades\Route;
use Skeleton\Http\Controllers\Api\Pricelist\DeleteController as PriceListDeleteController;
use Skeleton\Http\Controllers\Api\Pricelist\IndexController as PriceListIndexController;
use Skeleton\Http\Controllers\Api\Pricelist\UpdateController as PriceListUpdateController;
use Skeleton\Http\Controllers\Api\Pricelist\ViewController as PriceListViewController;
use Skeleton\Http\Controllers\Api\TagsController;

Route::admin()
    ->prefix('pricelist')
    ->group(function () {
        Route::get('/', [PriceListIndexController::class, 'index']);
        Route::get('{pricelist}', [PriceListViewController::class, 'view']);
        Route::post('create', [PriceListUpdateController::class, 'update']);
        Route::post('{pricelist}', [PriceListUpdateController::class, 'update']);
        Route::delete('{pricelist}', [PriceListDeleteController::class, 'delete']);
    });

Route::admin()
    ->group(function () {
        Route::get('tags', [TagsController::class, 'index']);
    });

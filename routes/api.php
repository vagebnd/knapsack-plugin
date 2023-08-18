<?php

use Knapsack\Compass\Support\Facades\Route;
use Skeleton\Http\Controllers\Api\Pricelist\DeleteController as PriceListDeleteController;
use Skeleton\Http\Controllers\Api\Pricelist\IndexController as PriceListIndexController;
use Skeleton\Http\Controllers\Api\Pricelist\UpdateController as PriceListUpdateController;
use Skeleton\Http\Controllers\Api\Pricelist\ViewController as PriceListViewController;
use Skeleton\Http\Controllers\Api\TagsController;
use Skeleton\Http\Controllers\Api\ThemeElements\ParseController;
use Skeleton\Http\Controllers\Api\ThemeTemplates\CheckImportController;
use Skeleton\Http\Controllers\Api\ThemeTemplates\ExportController;
use Skeleton\Http\Controllers\Api\ThemeTemplates\ImportController;

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

Route::admin()
    ->prefix('theme-templates')
    ->group(function () {
        Route::get('check-import', [CheckImportController::class, 'check']);
        Route::post('export', [ExportController::class, 'export']);
        Route::post('import', [ImportController::class, 'import']);
    });

Route::admin()
    ->prefix('theme-elements')
    ->group(function () {
        Route::post('parse', [ParseController::class, 'parse']);
    });

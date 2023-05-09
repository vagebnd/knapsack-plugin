<?php

use Knapsack\Compass\Support\Facades\Route;
use Skeleton\Controllers\AdminPages\PluginPageController;
use function Skeleton\Support\asset_url;

Route::adminPage('/galleries', function ($page) {
    $page->get('/', [PluginPageController::class, 'index']);
    $page->post('/', [PluginPageController::class, 'store']);
}, [
    'icon' => asset_url('img/icon.svg'),
]);

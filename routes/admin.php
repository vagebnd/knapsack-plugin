<?php

use Knapsack\Compass\Support\Facades\Route;
use Skeleton\Http\Controllers\AdminPages\PriceListController;
use function Skeleton\Support\resource_url;

Route::adminPage('/pricelist', function ($page) {
    $page->get('/', [PriceListController::class, 'index']);
}, [
    'icon' => resource_url('admin/assets/img/icon.svg'),
]);

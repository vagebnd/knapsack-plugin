<?php

use Knapsack\Compass\Support\Facades\Route;
use function Skeleton\Support\view;

Route::adminPage('Skeleton', function ($page) {
    $page->get('/', function () {
        view('admin.views.pages.test');
    });

    $page->get('export', function () {
        view('admin.views.pages.export');
    });

    $page->get('import', function () {
        view('admin.views.pages.import');
    });
});

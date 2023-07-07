<?php

use Knapsack\Compass\Support\Facades\App;
use Skeleton\Support\Http;

App::instance(Http::class, function () {
    return new Http();
});

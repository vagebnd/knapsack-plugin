<?php

use Knapsack\Compass\Support\Vite;

/*
|--------------------------------------------------------------------------
| Ensure the default theme assets are loaded
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {
    $vite = Vite::make();

    // TODO: Get this from config.
    $vite->asset('app.ts');
}, 100);

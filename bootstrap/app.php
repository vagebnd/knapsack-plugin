<?php

use Knapsack\Compass\Core\Http\Kernel;
use Knapsack\Compass\Plugin;
use Knapsack\Compass\Support\PlatformCheck;

if (! defined('ABSPATH')) {
    exit; // Disable direct access.
}

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
*/
require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Do the runtime check and validate the PHP and WordPress versions
|--------------------------------------------------------------------------
*/
(new PlatformCheck())->check();

/*
|--------------------------------------------------------------------------
| Create plugin instance
|
| This container will be returned and interact with it directly, this is
| required so we can repeat this process for each plugin.
|--------------------------------------------------------------------------
*/
new Kernel(
    $app = new Plugin(basename(dirname(__DIR__)))
);

/*
|--------------------------------------------------------------------------
| Load the plugins default settings
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/plugin/settings.php';

return $app;

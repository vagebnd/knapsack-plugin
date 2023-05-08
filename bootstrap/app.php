<?php

use Knapsack\Compass\Core\Http\Kernel;
use Knapsack\Compass\Plugin;
use Knapsack\Compass\Support\PlatformCheck;
use Skeleton\Support\Plugin as PluginApp;

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
| Create App instance
|--------------------------------------------------------------------------
*/
new Kernel(
    $app = new Plugin(basename(dirname(__DIR__)))
);

/*
|--------------------------------------------------------------------------
| Bind the app instance to the plugin
|--------------------------------------------------------------------------
*/
PluginApp::getInstance()
    ->setContainer($app);

/*
|--------------------------------------------------------------------------
| Load the plugins default settings
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/plugin/settings.php';

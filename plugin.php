<?php

/*
Plugin Name: Knapsack plugin boilerplate
Description: A plugin boilerplate for WordPress
Version: 0.0.1
Author: Vagebond
Author URI: vagebond.nl
Plugin URI: https://vagebond.nl
Text Domain: knapsack-plugin-boilerplate
Domain Path: /lang
*/

/*
|--------------------------------------------------------------------------
| Bootstrap the plugin
|--------------------------------------------------------------------------
*/
$app = require_once __DIR__.'/bootstrap/app.php';

// We now interact with the app instance directly.
// This allows us to repeat this process for each plugin.

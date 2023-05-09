<?php

if (! defined('ABSPATH')) {
    exit; // disable direct access
}

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

require_once __DIR__.'/bootstrap/app.php';

// // Adding an API endpoint
// add_action('rest_api_init', 'register_my_api_endpoint');

// function register_my_api_endpoint()
// {
//     register_rest_route('my-plugin/v1', '/my-endpoint', [
//         'methods' => 'GET',
//         'callback' => 'my_api_endpoint_handler',
//         'permission_callback' => function () {
//             return true;
//             // return current_user_can( 'edit_others_posts' );
//         },
//     ]);
// }

// // And API route registers a namespace, a route and a callback.

// function my_api_endpoint_handler($request)
// {
//     $data = ['message' => 'Hello Plugin!'];

//     return new WP_REST_Response($data, 200);
// }

<?php

/*
|--------------------------------------------------------------------------
| Ensure the default plugin assets are loaded
|--------------------------------------------------------------------------
*/

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_media();
}, 0);

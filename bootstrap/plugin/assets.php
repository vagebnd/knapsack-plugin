<?php

use function Skeleton\Support\vite;

/*
|--------------------------------------------------------------------------
| Ensure the default plugin assets are loaded
|--------------------------------------------------------------------------
*/

add_action('admin_enqueue_scripts', function () {
    vite()->asset('admin.ts');
});

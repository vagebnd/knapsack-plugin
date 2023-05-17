<?php

use Skeleton\Support\Factory;

add_action('plugins_loaded', function () {
    Factory::run('Blocks/Shortcodes');
});

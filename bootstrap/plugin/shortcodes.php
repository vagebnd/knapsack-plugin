<?php

use Skeleton\Support\BlockFactory;

add_action('plugins_loaded', function () {
    BlockFactory::run('Blocks/Shortcodes');
});

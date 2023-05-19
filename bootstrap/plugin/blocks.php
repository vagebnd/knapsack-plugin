<?php

use Skeleton\Blocks\Integrations\Elementor\Elementor;

add_action('plugins_loaded', function () {
    new Elementor();
});

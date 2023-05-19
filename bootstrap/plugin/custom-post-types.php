<?php

use function Skeleton\Support\app_path;
use Symfony\Component\Finder\Finder;

add_action('init', function () {
    $finder = Finder::create()
        ->in(app_path('CustomPostTypes'))
        ->files();

    foreach ($finder as $file) {
        $ClassName = 'Skeleton\\CustomPostTypes\\' . $file->getBasename('.php');
        $instance = new $ClassName();
        register_post_type($instance->name, $instance->args());
    }
});

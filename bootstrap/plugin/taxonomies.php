<?php

use function Skeleton\Support\app_path;
use Symfony\Component\Finder\Finder;

add_action('init', function () {
    $finder = Finder::create()
        ->in(app_path('Taxonomies'))
        ->files();

    foreach ($finder as $file) {
        $ClassName = 'Skeleton\\Taxonomies\\' . $file->getBasename('.php');
        $instance = new $ClassName();

        register_taxonomy($instance->name, $instance->postType, $instance->args());
    }
});

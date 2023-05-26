<?php

use function Skeleton\Support\app_path;
use Symfony\Component\Finder\Finder;

add_action('init', function () {
    $finder = Finder::create()
        ->in(app_path('Models'))
        ->files();

    foreach ($finder as $file) {
        $name = $file->getBasename('.php');
        $ClassName = 'Skeleton\\Models\\' . $name;
        $instance = new $ClassName();
        register_post_type($name, $instance->args());
    }
});

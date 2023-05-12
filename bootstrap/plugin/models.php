<?php

use function Skeleton\Support\app_path;
use Symfony\Component\Finder\Finder;

add_action('init', function () {
    $finder = Finder::create()
        ->in(app_path('Models'))
        ->files();

    foreach ($finder as $file) {
        $ClassName = 'Skeleton\\Models\\' . $file->getBasename('.php');
        $class = new $ClassName();
        register_post_type($class->name, $class->args());
    }
});

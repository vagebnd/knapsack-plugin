<?php

namespace Skeleton\Elementor;

use function Skeleton\Support\path;
use Symfony\Component\Finder\Finder;

class Factory
{
    public function __construct()
    {
        add_action('elementor/widgets/register', function ($manager) {
            $finder = Finder::create()
                ->in(path('elementor/widgets'))
                ->files();

            foreach ($finder as $file) {
                require_once $file->getRealPath();
                $Widget = 'Skeleton\\Elementor\\' . $file->getBasename('.php');
                $manager->register(new $Widget());
            }
        });
    }
}

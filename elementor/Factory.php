<?php

namespace Skeleton\Elementor;

use function Skeleton\Support\path;
use Symfony\Component\Finder\Finder;

class Factory
{
    public function __construct()
    {
        $this->registerControls();
        $this->registerWidgets();
    }

    private function registerControls()
    {
        add_action('elementor/controls/register', function ($manager) {
            $finder = Finder::create()
                ->in(path('elementor/controls'))
                ->files();

            foreach ($finder as $file) {
                require_once $file->getRealPath();
                $Control = 'Skeleton\\Elementor\\Controls\\' . $file->getBasename('.php');
                $manager->register(new $Control());
            }
        });
    }

    private function registerWidgets()
    {
        add_action('elementor/widgets/register', function ($manager) {
            $finder = Finder::create()
                ->in(path('elementor/widgets'))
                ->files();

            foreach ($finder as $file) {
                require_once $file->getRealPath();
                $Widget = 'Skeleton\\Elementor\\Widgets\\' . $file->getBasename('.php');
                $manager->register(new $Widget());
            }
        });
    }
}

<?php

namespace Skeleton\Blocks\Integrations\Elementor;

use function Skeleton\Support\path;

class Hello extends Elementor
{
    public function setup($manager)
    {
        // TODO: review if integrations should be autoloaded
        // We can also extract it and do the autoloading ourselves
        require_once path('elementor/includes/widgets/Hello.php');

        $manager->register(new \Skeleton\Elementor\Hello());
    }
}

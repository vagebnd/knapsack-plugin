<?php

namespace Skeleton\Elementor\Controls;

use function Skeleton\Support\vite;

class Pricelist extends \Elementor\Base_Data_Control
{
    public function get_type()
    {
        return 'pricelist';
    }

    protected function get_default_settings()
    {
        return [];
    }

    public function get_default_value()
    {
        return [];
    }

    public function content_template()
    {
        ?>
        <div id="vue">ja goed hoor</div>
		<?php
    }

    public function enqueue()
    {
        vite()->asset('blocks/elementor/pricelist.ts');
    }
}

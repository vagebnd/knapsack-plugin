<?php

namespace Skeleton\Elementor\Widgets;

use Knapsack\Compass\Support\Facades\Config;
use Skeleton\Blocks\Shortcodes\Pricelist as ShortcodesPricelist;
use Skeleton\Enums\PriceListType;

class Pricelist extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'pricelist';
    }

    public function get_title()
    {
        return esc_html__('Pricelist', 'elementor-addon');
    }

    public function get_icon()
    {
        return 'eicon-code';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_keywords()
    {
        return ['hello', 'world'];
    }

    protected function register_controls()
    {
        $textDomain = Config::get('app.text-domain');

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Pricelist', $textDomain),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pricelist',
            [
                'label' => esc_html__('Pricelist', $textDomain),
                'type' => 'pricelist',
                'types' => PriceListType::asSelectArray(),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo (new ShortcodesPricelist())->render($settings);
    }
}

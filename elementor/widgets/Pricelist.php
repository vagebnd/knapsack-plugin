<?php

namespace Skeleton\Elementor\Widgets;

use Skeleton\Blocks\Shortcodes\Pricelist as ShortcodesPricelist;
use Skeleton\Models\PriceList as ModelsPriceList;

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
        $priceLists = ModelsPriceList::all();

        $priceListOptions = $priceLists
            ->mapWithKeys(function ($priceList) {
                return [$priceList->ID => $priceList->post_title];
            })
            ->toArray();

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Pricelist', 'elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pricelist',
            [
                'label' => esc_html__('Pricelist', 'elementor-addon'),
                'type' => 'pricelist',
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

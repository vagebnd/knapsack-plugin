<?php

namespace Skeleton\Elementor\Widgets;

use Knapsack\Compass\Support\Facades\Config;
use Skeleton\Blocks\Shortcodes\Pricelist;
use Skeleton\Enums\PriceListType;
use function Skeleton\Support\vite;

class Pricelists extends \Elementor\Widget_Base
{
    private $_widget_id = 'pricelists';

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        vite()->asset('shared/assets/ts/blocks/init.ts');
    }

    public function get_name()
    {
        return $this->_widget_id;
    }

    public function get_title()
    {
        return esc_html__('Pricelists', 'elementor-addon');
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

    public function get_script_depends()
    {
        return [$this->_widget_id.'-script'];
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
        $attrs = $this->get_settings_for_display();
        $attrs['elementID'] = $this->_widget_id . '-' . $this->get_id();

        echo (new Pricelist())->render($attrs);
    }
}

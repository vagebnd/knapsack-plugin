<?php

namespace Skeleton\Core;

use Knapsack\Compass\Support\Facades\Config;

class Taxonomy
{
    public $id = '';
    public $name = '';
    public $postType = '';

    public $args = [];
    public $capabilities = [];
    public $default_term;
    public $description = '';
    public $hierarchical = false;
    public $labels = [];
    public $meta_box_cb = false;
    public $meta_box_sanitize_cb = false;
    public $public = true;
    public $publicly_queryable;
    public $query_var;
    public $rest_base;
    public $rest_controller_class = 'WP_REST_Terms_Controller';
    public $rest_namespace = 'wp/v2';
    public $rewrite = true;
    public $show_admin_column = false;
    public $show_in_menu;
    public $show_in_nav_menus = false;
    public $show_in_quick_edit = true;
    public $show_in_rest = false;
    public $show_tagcloud = true;
    public $show_ui;
    public $sort = null;
    public $update_count_callback;

    public function args()
    {
        return [
            'args' => $this->args,
            'capabilities' => $this->capabilities,
            'default_term' => $this->default_term,
            'description' => $this->description,
            'hierarchical' => $this->hierarchical,
            'labels' => $this->labels,
            'meta_box_cb' => $this->meta_box_cb,
            'meta_box_sanitize_cb' => $this->meta_box_sanitize_cb,
            'public' => $this->public,
            'publicly_queryable' => isset($this->publicly_queryable) ? $this->publicly_queryable : $this->public,
            'query_var' => isset($this->query_var) ? $this->query_var : $this->name,
            'rest_base' => isset($this->rest_base) ? $this->rest_base : $this->name,
            'rest_controller_class' => $this->rest_controller_class,
            'rest_namespace' => $this->rest_namespace,
            'rewrite' => $this->rewrite,
            'show_admin_column' => $this->show_admin_column,
            'show_in_menu' => isset($this->show_in_menu) ? $this->show_in_menu : $this->getShowUi(),
            'show_in_nav_menus' => isset($this->show_in_nav_menus) ? $this->show_in_nav_menus : $this->public,
            'show_in_quick_edit' => isset($this->show_in_quick_edit) ? $this->show_in_quick_edit : $this->getShowUi(),
            'show_in_rest' => $this->show_in_rest,
            'show_tagcloud' => $this->show_tagcloud,
            'show_ui' => $this->getShowUi(),
            'sort' => $this->sort,
            'update_count_callback' => $this->update_count_callback,
        ];
    }

    public function getShowUi()
    {
        return isset($this->show_ui) ? $this->show_ui : $this->public;
    }

    public function labels(): array
    {
        $textDomain = Config::get('app.text_domain');

        $defaults = [
          'name'                    => $this->name,
          'singular_name'           => $this->name,
          'menu_name'               => $this->name,
          'all_items'               => sprintf(__('All %s', $textDomain), $this->name),
          'edit_item'               => sprintf(__('Edit %s', $textDomain), $this->name),
          'view_item'               => sprintf(__('View %s', $textDomain), $this->name),
          'update_item'             => sprintf(__('Update %s', $textDomain), $this->name),
          'add_new_item'            => sprintf(__('Add New %s', $textDomain), $this->name),
          'new_item_name'           => sprintf(__('New %s Name', $textDomain), $this->name),
          'parent_item'             => sprintf(__('Parent %s', $textDomain), $this->name),
          'parent_item_colon'       => sprintf(__('Parent %s:', $textDomain), $this->name),
          'search_items'            => sprintf(__('Search %s', $textDomain), $this->name),
          'popular_items'           => sprintf(__('Popular %s', $textDomain), $this->name),
          'add_or_remove_items'     => sprintf(__('Add or remove %s', $textDomain), $this->name),
          'choose_from_most_used'   => sprintf(__('Choose from most used %s', $textDomain), $this->name),
          'not_found'               => sprintf(__('No %s found', $textDomain), $this->name),
          'back_to_items'           => sprintf(__('&larr; Back to %s', $textDomain), $this->name),
        ];

        if (empty($this->labels)) {
            return $defaults;
        }

        return array_merge(
            $defaults,
            $this->labels
        );
    }
}

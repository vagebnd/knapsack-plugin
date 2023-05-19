<?php

namespace Skeleton\Core;

use Knapsack\Compass\Support\Facades\Config;

class CustomPostType
{
    public $id = '';
    public $name = '';
    public $plural = '';
    public $labels = [];
    public $description = '';
    public $public = true;
    public $hierarchical = false;
    public $excludeFromSearch = true;
    public $publiclyQueryable = true;
    public $showUI = true;
    public $showInMenu = false;
    public $showInNavMenus = true;
    public $showInAdminBar = true;
    public $menuPosition = null;
    public $menuIcon = '';
    public $capabilityType = 'post';
    public $capabilities = [];
    public $mapMetaCap = true;
    public $supports = [];
    public $registerMetaBoxCallback = null;
    public $taxonomies = [];
    public $hasArchive = false;
    public $rewrite = [];
    public $slug = 'wp_kirk_slug';
    public $withFront = true;
    public $feeds = false;
    public $pages = true;
    public $epMask = EP_PERMALINK;
    public $queryVar = 'wp_kirk_vars';
    public $canExport = true;
    public $deleteWithUser = false;

    public static function get(array $args = [])
    {
        return get_posts(array_merge([
            'post_type' => self::getName(),
        ], $args));
    }

    public static function query(array $args = [])
    {
        return new \WP_Query(array_merge([
            'post_type' => self::getName(),
        ], $args));
    }

    public static function save(array $args)
    {
        $args['post_type'] = self::getName();
        $args['post_status'] = 'publish';

        return wp_insert_post($args);
    }

    public static function update(array $args)
    {
        return wp_update_post($args);
    }

    public static function delete(int $id)
    {
        return wp_delete_post($id);
    }

    public static function getName()
    {
        return (new self())->name;
    }

    public function args(): array
    {
        return [
          'labels'               => $this->labels(),
          'description'          => $this->description,
          'public'               => $this->public,
          'hierarchical'         => $this->hierarchical,
          'exclude_from_search'  => $this->excludeFromSearch,
          'publicly_queryable'   => $this->publiclyQueryable,
          'show_ui'              => $this->showUI,
          'show_in_menu'         => $this->showInMenu,
          'show_in_nav_menus'    => $this->showInNavMenus,
          'show_in_admin_bar'    => $this->showInAdminBar,
          'menu_position'        => $this->menuPosition,
          'menu_icon'            => $this->menuIcon(),
          'capability_type'      => $this->capabilityType,
          'capabilities'         => $this->capabilities,
          'map_meta_cap'         => $this->mapMetaCap,
          'supports'             => $this->supports(),
          'register_meta_box_cb' => $this->registerMetaBoxCallback,
          'taxonomies'           => $this->taxonomies,
          'has_archive'          => $this->hasArchive,
          'rewrite'              => $this->rewrite(),
          'query_var'            => $this->queryVar,
          'can_export'           => $this->canExport,
          'delete_with_user'     => $this->deleteWithUser,
        ];
    }

    public function labels(): array
    {
        $textDomain = Config::get('app.text-domain');

        $defaults = [
          'name'               => $this->plural,
          'singular_name'      => $this->name,
          'menu_name'          => $this->name,
          'name_admin_bar'     => $this->name,
          'add_new'            => sprintf(__('Add %s', $textDomain), $this->name),
          'add_new_item'       => sprintf(__('Add new  %s', $textDomain), $this->name),
          'edit_item'          => sprintf(__('Edit %s', $textDomain), $this->name),
          'new_item'           => sprintf(__('New %s', $textDomain), $this->name),
          'view_item'          => sprintf(__('View %s', $textDomain), $this->name),
          'search_items'       => sprintf(__('Search %s', $textDomain), $this->plural),
          'not_found'          => sprintf(__('No %s found', $textDomain), $this->plural),
          'not_found_in_trash' => sprintf(__('No %s found in trash', $textDomain), $this->plural),
          'all_items'          => $this->plural,
          'archive_title'      => $this->name,
          'parent_item_colon'  => '',
        ];

        if (empty($this->labels)) {
            return $defaults;
        }

        return array_merge(
            $defaults,
            $this->labels
        );
    }

    public function supports(): array
    {
        if (empty($this->supports)) {
            return [
              'title',
              'editor',
              'author',
              'thumbnail',
              'excerpt',
              'trackbacks',
              'custom-fields',
              'comments',
              'revisions',
              'post-formats',
            ];
        }

        return $this->supports;
    }

    public function rewrite(): array
    {
        if (empty($this->rewrite)) {
            return [
              'slug'       => $this->slug,
              'with_front' => $this->withFront,
              'pages'      => $this->pages,
              'ep_mask'    => $this->epMask,
            ];
        }

        return $this->rewrite;
    }

    public function menuIcon()
    {
        return '';
    }
}

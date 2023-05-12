<?php

namespace Skeleton\Core;

class Model
{
    public $id = '';
    public $name = '';
    public $plural = '';
    public $labels = [];
    public $description = '';
    public $public = false;
    public $hierarchical = false;
    public $excludeFromSearch = true;
    public $publiclyQueryable = false;
    public $showUI = true;
    public $showInMenu = true;
    public $showInNavMenus = true;
    public $showInAdminBar = true;
    public $menuPosition = null;
    public $menuIcon = '';
    public $capabilityType = 'post';
    public $capabilities = [];
    public $mapMetaCap = false;
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

    public static function query(array $args)
    {
        return new \WP_Query($args);
    }

    public static function save(array $args)
    {
        $args['post_type'] = (new self())->name;
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
        $defaults = [
          'name'               => $this->plural,
          'singular_name'      => $this->name,
          'menu_name'          => $this->name,
          'name_admin_bar'     => $this->name,
          'add_new'            => "Add {$this->name}",
          'add_new_item'       => "Add New {$this->name}",
          'edit_item'          => "Edit {$this->name}",
          'new_item'           => "New {$this->name}",
          'view_item'          => "View {$this->name}",
          'search_items'       => "Search {$this->name}",
          'not_found'          => "No {$this->name} found",
          'not_found_in_trash' => "No {$this->name} found in trash",
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

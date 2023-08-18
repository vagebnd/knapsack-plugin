<?php

namespace Skeleton\Support;

use Knapsack\Compass\Support\Collections\Arr;
use Knapsack\Compass\Support\Filesystem;
use SplFileInfo;

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

class TemplateImporter
{
    private Filesystem $fileSystem;

    private $importedPosts = [];

    public function __construct()
    {
        global $wp_filesystem;
        $this->fileSystem = new Filesystem($wp_filesystem);
    }

    public function import($path)
    {
        $this->importAttachments($path);
        $this->importData($path);
    }

    private function importData($path)
    {
        $dataPath = implode(DIRECTORY_SEPARATOR, [$path, 'data.json']);

        $data = $this->fileSystem->get($dataPath);
        $data = $this->parseData($data);
        $data = json_decode($data, true);

        $posts = Arr::get($data, 'posts', []);
        $terms = Arr::get($data, 'terms', []);
        $navigations = Arr::get($data, 'navigations', []);

        $this->clean();
        $this->importTerms($terms);
        $this->importPosts($posts);
        $this->importNavigations($navigations);
    }

    private function clean()
    {
        collect(get_theme_mods())
            ->each(function ($option) {
                delete_option($option);
            });

        // TODO:: remove this later on.
        $posts = collect(get_posts([
            'post_type' => ['page', 'post', 'pricelistsection', 'pricelistitem', 'pricelistitem', 'pricelist', 'nav_menu_item'],
            'post_status' => 'any',
            'posts_per_page' => -1,
        ]));

        $posts->each(function ($post) {
            wp_delete_post($post->ID, true);
        });

        // TODO:: remove this later on. Or perhaps not?
        collect(get_terms([
            'taxonomy' => ['nav_menu'],
            'hide_empty' => false,
        ]))
        ->each(function ($term) {
            wp_delete_term($term->term_id, $term->taxonomy);
        });
    }

    private function importPosts($posts)
    {
        $this->importedPosts = collect($posts)
            ->map(function ($post) {
                return Arr::forget($post, 'ID');
            })
            ->mapWithKeys(function ($post) {
                $postID = wp_insert_post($post);
                $post['ID'] = $postID;
                return [$postID => $post];
            });
    }

    private function importTerms($terms)
    {
        $currentTerms = collect(get_terms());

        collect($terms)
            ->filter(function ($term) use ($currentTerms) {
                return ! $currentTerms->contains(function ($currentTerm) use ($term) {
                    return $currentTerm->slug === $term['slug'] && $currentTerm->taxonomy === $term['taxonomy'];
                });
            })
            ->each(function ($term) {
                wp_insert_term($term['name'], $term['taxonomy'], [
                    'slug' => $term['slug'],
                ]);
            });
    }

    private function importNavigations($navigations)
    {
        collect($navigations)
            ->each(function ($navigation) {
                $name = Arr::get($navigation, 'name');
                $slug = Arr::get($navigation, 'slug');
                $items = Arr::get($navigation, 'items', []);
                $location = Arr::get($navigation, 'location');

                if (term_exists($slug, 'nav_menu')) {
                    $term = get_term_by('slug', $slug, 'nav_menu');
                } else {
                    $term = (object) wp_insert_term($name, 'nav_menu', [
                        'slug' => $slug,
                    ]);
                }

                $locations = get_theme_mod('nav_menu_locations', []);
                $locations[$location] = $term->term_id;
                set_theme_mod('nav_menu_locations', $locations);

                $this->importMenuItems($items, $term, 0);
            });
    }

    private function importMenuItems($items, $term, $parentID)
    {
        collect($items)
            ->each(function ($item) use ($term, $parentID) {
                $post = $this->getPostBySlug($item['postSlug']);

                $navItemPostID = wp_insert_post([
                    'post_title' => $item['title'],
                    'post_type' => 'nav_menu_item',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'meta_input' => [
                        '_menu_item_type' => 'post_type',
                        '_menu_item_menu_item_parent' => $parentID,
                        '_menu_item_object_id' => $post['ID'],
                        '_menu_item_object' => 'page',
                        '_menu_item_target' => '',
                        '_menu_item_classes' => 'a:1:{i:0;s:0:"";}',
                        '_menu_item_xfn' => '',
                        '_menu_item_url' => '',
                    ],
                ]);

                wp_set_object_terms($navItemPostID, $term->term_id, 'nav_menu');

                $items = Arr::get($item, 'items', []);

                if (count($items) > 0) {
                    $this->importMenuItems($items, $term, $navItemPostID);
                }
            });
    }

    private function getPostBySlug($slug)
    {
        return collect($this->importedPosts)
            ->first(function ($post) use ($slug) {
                return $post['post_name'] === $slug;
            });
    }

    private function parseData($data)
    {
        $attachments = collect(get_posts([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'orderby' => 'post_date',
            'order' => 'DESC',
            ]))
            ->map(function ($post) {
                $post->importTitle = get_post_meta($post->ID, createMetaKey('import_title'), true);
                return $post;
            })
            ->pluck('guid', 'importTitle');

        $data = preg_replace_callback('/http[^"]*\.[^"]*[^"\\\\]/', function ($matches) use ($attachments) {
            $url = $matches[0];
            $filename = basename($url);
            $filename = trim($filename, '\"\\');

            if ($attachments->has($filename)) {
                $result =  json_encode($attachments->get($filename)) ;
                $result = str_replace('"', '', $result);
                return $result;
            }

            return $matches[0];
        }, $data);

        $data = preg_replace('#http:\\\/\\\/[^\\/]*#', get_site_url(), $data);

        return $data;
    }

    private function importAttachments($path)
    {
        $attachmentsPath = implode(DIRECTORY_SEPARATOR, [$path, 'attachments']);
        $attachments = $this->fileSystem->files($attachmentsPath);

        foreach ($attachments as $attachment) {
            $this->importAttachment($attachment);
        }
    }

    private function importAttachment(SplFileInfo $attachment)
    {
        $path = $attachment->getPathname();

        $postID = media_handle_sideload([
            'name' => $attachment->getFilename(),
            'type' => wp_check_filetype($path)['type'],
            'tmp_name' => $path,
        ], 0);

        if ($postID instanceof \WP_Error) {
            return;
        }

        update_post_meta($postID, createMetaKey('import_title'), $attachment->getFilename());
    }
}

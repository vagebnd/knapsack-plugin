<?php

namespace Skeleton\Support;

use Knapsack\Compass\Support\Collections\Arr;
use Knapsack\Compass\Support\Filesystem;
use ZipArchive;

class TemplateExporter
{
    private Filesystem $fileSystem;

    public function __construct()
    {
        global $wp_filesystem;

        $this->fileSystem = new Filesystem($wp_filesystem);
    }

    public function export()
    {
        $exportPath = implode(DIRECTORY_SEPARATOR, [Arr::get(wp_upload_dir(), 'path'), 'export']);
        $attachmentPath = implode(DIRECTORY_SEPARATOR, [$exportPath, 'attachments']);
        $archivePath = implode(DIRECTORY_SEPARATOR, [$exportPath, 'archive.zip']);

        try {
            $this->createDirectories($exportPath, $attachmentPath);
            $this->saveAttachments($attachmentPath);
            $this->saveData($exportPath);
            $this->createArchive($exportPath, $archivePath);

            return $archivePath;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createArchive($exportPath, $archivePath)
    {
        $zip = new ZipArchive();
        $zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = $this->fileSystem->allFiles($exportPath);

        foreach ($files as $file) {
            $localPath = str_replace($exportPath . DIRECTORY_SEPARATOR, '', $file);
            $zip->addFile($file, $localPath);
        }

        $zip->close();
    }

    private function saveAttachments($attachmentPath)
    {
        return collect(get_posts([
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
        ]))
        ->map(function ($attachment) use ($attachmentPath) {
            $fileUrl = $attachment->guid;
            $attachmentPath = $attachmentPath . '/' . basename($fileUrl);
            $response = wp_remote_get($fileUrl);

            if (is_wp_error($response)) {
                return false;
            }

            $body = wp_remote_retrieve_body($response);
            $result = file_put_contents($attachmentPath, $body);

            return $result !== false ? $attachmentPath : false;
        })
        ->filter()
        ->toArray();
    }

    private function createDirectories($exportPath, $attachmentPath)
    {
        if ($this->fileSystem->exists($exportPath)) {
            $this->fileSystem->deleteDirectory($exportPath);
        }

        $this->fileSystem->makeDirectory($attachmentPath, 0755, true);
    }

    private function saveData($exportPath)
    {
        $path = implode(DIRECTORY_SEPARATOR, [$exportPath, 'data.json']);

        $taxonomies = get_taxonomies();
        $navigations = $this->getNavigations();
        $posts = $this->getPosts($navigations);
        $terms = $this->getTerms($posts);

        $data = [
            'taxonomies' => $taxonomies,
            'terms' => $terms,
            'navigations' => $navigations,
            'posts' => $posts,
        ];

        $this->fileSystem->put($path, json_encode($data));

        return [$path];
    }

    public function getNavigations()
    {
        $navMenuLocations = get_nav_menu_locations();
        $navigationIds = Arr::values($navMenuLocations);
        $navigations = get_terms(['include' => $navigationIds]);

        $result = collect($navigations)
            ->filter(function ($navigation) {
                return wp_get_nav_menu_items($navigation->term_id) !== false;
            })
            ->map(function ($navigation) use ($navMenuLocations) {
                return [
                    'name' => $navigation->name,
                    'slug' => $navigation->slug,
                    'items' => $this->getNavigationItems($navigation),
                    'location' => Arr::get(array_flip($navMenuLocations), $navigation->term_id),
                ];
            })
            ->toArray();

        return $result;
    }

    private function getNavigationItems($navigation)
    {
        $items = collect(wp_get_nav_menu_items($navigation->term_id))
            ->map(function ($item) {
                $meta = $this->getPostMeta($item->ID);

                return [
                    'id' => $item->ID,
                    'title' => $item->post_title,
                    'parent_id' => Arr::get($meta, '_menu_item_menu_item_parent'),
                    'postSlug' => get_post_field('post_name', Arr::get($meta, '_menu_item_object_id')),
                    'items' => [],
                ];
            })
            ->toArray();

        return $this->cleanNavigationTree($this->buildNavigationTree($items, 0));
    }

    private function buildNavigationTree($flatArray, $parentID = null)
    {
        $tree = [];

        collect($flatArray)
            ->filter(function ($item) use ($parentID) {
                return $item['parent_id'] == $parentID;
            })
            ->each(function ($item) use ($flatArray, &$tree) {
                $items = $this->buildNavigationTree($flatArray, $item['id']);
                if (! empty($items)) {
                    $item['items'] = $items;
                }
                $tree[] = $item;
            });

        return $tree;
    }

    private function cleanNavigationTree($tree)
    {
        return collect($tree)
            ->map(function ($item) {
                $item = Arr::forget($item, ['id', 'parent_id']);
                $item['items'] = $this->cleanNavigationTree($item['items']);
                return $item;
            })
            ->toArray();
    }

    private function getPosts($navigations)
    {
        $slugs = $this->getSlugs($navigations, []);
        $postTypes = array_diff(get_post_types(), ['post', 'nav_menu_item', 'elementor_library']);

        $posts = get_posts([
            'post_type' => $postTypes,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        $result = collect($posts)
            ->filter(function ($post) use ($slugs) {
                if ($post->post_type !== 'page') {
                    return true;
                }

                return in_array($post->post_name, $slugs);
            })
            ->map(function ($post) {
                $post = get_object_vars($post);
                $post = Arr::only($post, [
                    'ID',
                    'post_content',
                    'post_title',
                    'post_excerpt',
                    'post_status',
                    'post_name',
                    'post_parent',
                    'menu_order',
                    'post_type',
                    'meta_input',
                ]);

                $post['meta_input'] = $this->getPostMeta($post['ID']);
                $post['tags_input'] = $this->getPostTerms($post['ID']);

                return $post;
            })
            ->toArray();

        return $result;
    }

    private function getSlugs($items, $slugs)
    {
        foreach ($items as $item) {
            if (Arr::get($item, 'postSlug', false)) {
                $slugs[] = $item['postSlug'];
            }

            if (Arr::get($item, 'items', false)) {
                $slugs = $this->getSlugs($item['items'], $slugs);
            }
        }

        return $slugs;
    }

    private function getTerms($posts)
    {
        return collect($posts)
            ->map(function ($post) {
                return wp_get_post_terms($post['ID']);
            })
            ->flatten(1)
            ->keyBy('slug')
            ->map(function ($term) {
                return Arr::only(get_object_vars($term), [
                    'name',
                    'slug',
                    'taxonomy',
                ]);
            });
    }

    private function getPostMeta($postID)
    {
        return collect(get_post_meta($postID))
            ->map(function ($meta, $key) {
                $value = $meta[0];

                if (is_serialized($value)) {
                    $value = unserialize($value);
                }

                if ($key === '_elementor_data') {
                    $value = add_host_placeholder($value);
                }

                return $value;
            })
            ->toArray();
    }

    private function getPostTerms($postID)
    {
        return collect(wp_get_post_terms($postID))
            ->pluck('slug')
            ->toArray();
    }
}

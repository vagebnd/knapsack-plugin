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

        foreach (Arr::get($data, 'posts') as $post) {
            $postID = wp_insert_post(Arr::only($post, [
                'post_title',
                'post_content',
                'post_name',
                'post_type',
                'post_status',
                'post_author',
                'menu_order',
                'guid',
                'post_parent',
            ]));

            foreach (Arr::get($post, 'meta') as $key => $value) {
                $value = array_shift($value);

                if (is_serialized($value)) {
                    $value = unserialize($value);
                }

                add_post_meta($postID, $key, $value);
            }
        }
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

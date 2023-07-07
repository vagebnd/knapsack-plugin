<?php

namespace Skeleton\Support;

use Illuminate\Support\Arr;
use Knapsack\Compass\Support\Filesystem;
use WP_Query;
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

    private function saveData($exportPath)
    {
        $path = implode(DIRECTORY_SEPARATOR, [$exportPath, 'data.json']);

        $data = [
            'posts' => $this->getPostData(),
        ];

        $this->fileSystem->put($path, json_encode($data));

        return [$path];
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

    private function getPostData()
    {
        $query = new WP_Query([
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        return collect($query->posts)
            ->map(function ($post) {
                return get_object_vars($post);
            })
            ->map(function ($post) {
                $post = Arr::only($post, [
                    'post_title',
                    'post_content',
                    'post_name',
                    'post_type',
                    'post_status',
                    'post_author',
                    'menu_order',
                    'guid',
                    'post_parent',
                ]);

                $post['meta'] = get_post_meta($post['ID']);
                return $post;
            })
            ->toArray();
    }
}

<?php

namespace Skeleton\Http\Controllers\Api\ThemeTemplates;

use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Collections\Arr;
use Knapsack\Compass\Support\Facades\Config;
use Knapsack\Compass\Support\Filesystem;
use Knapsack\Compass\Support\Request;
use function Skeleton\Support\http;
use Skeleton\Support\TemplateImporter;

class ImportController extends Controller
{
    private $fileSystem;

    public function import(Request $request)
    {
        $request->validate([
            'hash' => ['min:2', 'max:20', 'nullable'],
        ]);

        global $wp_filesystem;
        $this->fileSystem = new Filesystem($wp_filesystem);

        $hash = $request->input('hash');

        $importPath = implode(DIRECTORY_SEPARATOR, [Arr::get(wp_upload_dir(), 'path'), 'import']);
        $kitArchivePath = implode(DIRECTORY_SEPARATOR, [$importPath, 'kit.zip']);
        $kitPath = implode(DIRECTORY_SEPARATOR, [$importPath, 'kit']);

        $this->createDirectory($importPath);
        $this->downloadKit($kitArchivePath, $hash);
        $this->unzipKit($kitArchivePath, $kitPath);
        $this->importKit($kitPath);

        return $hash;
    }

    private function createDirectory($importPath)
    {
        if ($this->fileSystem->exists($importPath)) {
            $this->fileSystem->deleteDirectory($importPath);
        }

        $this->fileSystem->makeDirectory($importPath, 0755, true);
    }

    private function downloadKit($path, $hash)
    {
        $fileHandle = fopen($path, 'w');

        $response = http()
            ->baseUrl(Config::get('theme-manager.api-url'))
            ->withToken(Config::get('theme-manager.api-key'))
            ->get('theme-templates/' . $hash . '/download', [
                'stream' => true,
            ]);

        $stream = $response->getBody();
        $fileHandle = fopen($path, 'w');

        while (! $stream->eof()) {
            fwrite($fileHandle, $stream->read(4096));
        }

        fclose($fileHandle);
    }

    private function unzipKit($kitArchivePath, $kitPath)
    {
        unzip_file($kitArchivePath, $kitPath);
    }

    private function importKit($kitPath)
    {
        $importer = new TemplateImporter();
        $importer->import($kitPath);
    }
}

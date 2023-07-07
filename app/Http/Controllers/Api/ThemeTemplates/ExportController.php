<?php

namespace Skeleton\Http\Controllers\Api\ThemeTemplates;

use Illuminate\Support\Str;
use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Facades\Config;
use Knapsack\Compass\Support\Request;
use function Skeleton\Support\http;
use Skeleton\Support\TemplateExporter;
use TusPhp\Tus\Client;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'title' => ['min:2', 'max:100', 'nullable'],
            'hash' => ['min:2', 'max:20', 'nullable'],
        ]);

        $hash = $request->input('hash');
        $title = $request->input('title');
        $isCreating = empty($hash);
        $data = ['title' => $title];

        $exporter = new TemplateExporter();
        $path = $exporter->export();

        $this->getTusClient()
            ->file($path)
            ->upload();

        $data = [
            'title' => $title,
            'theme' => Config::get('theme-manager.theme'),
        ];

        $url = $isCreating
            ? 'theme-templates/create'
            : 'theme-templates/' . $hash;

        http()
            ->baseUrl(Config::get('theme-manager.api-url'))
            ->withToken(Config::get('theme-manager.api-key'))
            ->post($url, $data);

        return $path;
    }

    private function getTusClient()
    {
        $tusClient = new Client(Config::get('theme-manager.app-url'));
        $tusClient->setApiPath('/upload');
        $tusClient->setKey(Str::random(32));
        return $tusClient;
    }
}

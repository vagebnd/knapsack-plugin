<?php

namespace Skeleton\Support;

use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Support\Env;
use Knapsack\Compass\Support\Facades\Config;
use Knapsack\Compass\Support\Vite;

function app($abstract = null, array $parameters = [])
{
    if (is_null($abstract)) {
        return Plugin::getInstance();
    }

    return Plugin::getInstance()->make($abstract, $parameters);
}

function app_path($path = '')
{
    return path('app/' . $path);
}

function upload_path($path)
{
    return trailingslashit(wp_upload_dir()['basedir']) . ltrim($path, '/');
}

function asset_url($path = '')
{
    return resource_url('assets/' . $path);
}

function can($capability)
{
    return current_user_can($capability);
}

function createMetaKey($key)
{
    return '_' . Config::get('app.name') . '_' . $key;
}

function env($key, $default = null)
{
    return app(Env::class)->get($key, $default);
}

function http()
{
    return app(Http::class);
}

function path($path = '')
{
    return __DIR__ . '/../../' . $path;
}

function redirect($page)
{
    wp_redirect("admin.php?page={$page}");
    exit;
}

function resource_path($path = '')
{
    return path('resources/' . $path);
}

function resource_url($path = '')
{
    return url('resources/' . $path);
}

function storage_path($path = '')
{
    return path('storage/' . $path);
}

function url($path = '')
{
    return plugins_url($path, path('../'));
}

function view(string $name, array $attributes = [])
{
    echo app(ViewContract::class)->render($name, $attributes);
}

function vite()
{
    return new Vite('assets', Plugin::getInstance()->getContainer());
}

function wp_log($value)
{
    $file_path = path('wp.log');
    $file_handle = fopen($file_path, 'a');
    fwrite($file_handle, $value . PHP_EOL);
    fclose($file_handle);
}

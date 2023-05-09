<?php

namespace Skeleton\Support;

use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Support\Vite;

if (! function_exists('path')) {
    function path($path = '')
    {
        return __DIR__ . '/../../' . $path;
    }
}

if (! function_exists('url')) {
    function url($path = '')
    {
        return plugins_url($path, path('../'));
    }
}

if (! function_exists('resource_url')) {
    function resource_url($path = '')
    {
        return url('resources/' . $path);
    }
}

if (! function_exists('asset_url')) {
    function asset_url($path = '')
    {
        return resource_url('assets/' . $path);
    }
}

if (! function_exists('app')) {
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Plugin::getInstance();
        }

        return Plugin::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('view')) {
    function view(string $name, array $attributes = [])
    {
        echo app(ViewContract::class)->render($name, $attributes);
    }
}

if (! function_exists('can')) {
    function can($capability)
    {
        return current_user_can($capability);
    }
}

if (! function_exists('vite')) {
    function vite()
    {
        return new Vite('assets', Plugin::getInstance()->getContainer());
    }
}

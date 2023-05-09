<?php

namespace Skeleton\Support;

use Knapsack\Compass\App;
use Knapsack\Compass\Support\Traits\ForwardsCalls;

class Plugin
{
    use ForwardsCalls;

    protected static $instance = null;

    protected App $container;

    private function __construct()
    {
        // Don't allow instantiation.
    }

    public function setContainer(App $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->container, $method, $parameters);
    }
}

<?php

namespace Skeleton\Blocks\Shortcodes;

use Illuminate\Support\Arr;
use ReflectionObject;
use Skeleton\Blocks\Contracts\IntegrationContract;
use Skeleton\Support\BlockFactory;

abstract class Shortcode
{
    public static $signature;

    public array $integrations = [];

    /**
     * @var IntegrationContract[]
     */
    private array $integrationInstances = [];

    abstract public function render($attrs, $content = null);

    public function __construct()
    {
        if (empty($this::$signature)) {
            throw new \Exception('Signature is not set');
        }

        $this->integrationInstances = BlockFactory::run('Blocks/Integrations', (new ReflectionObject($this))->getShortName());

        foreach ($this->integrationInstances as $instance) {
            if (! $instance instanceof IntegrationContract) {
                throw new \Exception("{$instance} must implement ".IntegrationContract::class);
            }
        }

        add_shortcode($this::$signature, function () {
            return $this->beforeRender(...func_get_args());
        });
    }

    private function beforeRender($attrs, $content = null)
    {
        $attrs = Arr::wrap($attrs);

        foreach ($this->integrationInstances as $integration) {
            if ($integration->shouldRun($attrs)) {
                $attrs = $integration->prepareData($attrs);
            }
        }

        return $this->render($attrs, $content);
    }
}

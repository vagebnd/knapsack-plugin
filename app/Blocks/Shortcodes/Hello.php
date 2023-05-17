<?php

namespace Skeleton\Blocks\Shortcodes;

use Knapsack\Compass\Contracts\ViewContract;
use function Skeleton\Support\app;

class Hello extends Shortcode
{
    public static $signature = 'hello';

    public function render($attrs, $content = null)
    {
        // Echo in shortcode gives gutenberg error.
        // Preferably the "view" helper doesn't echo but returns
        // Then when we render the controller is should echo or ob_get_clean
        return app(ViewContract::class)->render('shortcodes.hello', [
            'content' => $content,
            'attrs' => $attrs,
        ]);
    }
}

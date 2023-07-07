<?php

namespace Skeleton\Http\Controllers\Api\ThemeTemplates;

use Knapsack\Compass\Routing\Controller;

class CheckImportController extends Controller
{
    public function check()
    {
        return [
            'canImport' => true,
            'actions' => [],
        ];
    }
}

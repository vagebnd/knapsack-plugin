<?php

namespace Skeleton\Controllers\AdminPages;

use Knapsack\Compass\Routing\Controllers\AdminPageController;
use Knapsack\Compass\Support\Request;
use function Skeleton\Support\view;

class PluginPageController extends AdminPageController
{
    public function index()
    {
        $this->authorize('manage_options');

        return view('index');
    }

    public function store(Request $request)
    {
        // For some reason we need to prevent the admin headers from being sent
        // before we can redirect. Adding the get parameter noheaders=true works.
        // This is a shitty workaround and we should find a better way to do this.
    }
}

<?php

namespace Skeleton\Http\Controllers\AdminPages;

use Knapsack\Compass\Routing\Controllers\AdminPageController;
use Knapsack\Compass\Support\Request;
use function Skeleton\Support\view;

class PriceListController extends AdminPageController
{
    public function index(Request $request)
    {
        $this->authorize('manage_options');

        return view('pages.pricelist');
    }
}

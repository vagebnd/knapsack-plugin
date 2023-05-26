<?php

namespace Skeleton\Http\Controllers\AdminPages;

use Knapsack\Compass\Routing\Controllers\AdminPageController;
use Knapsack\Compass\Support\Request;
use Skeleton\Models\PriceList;
use function Skeleton\Support\view;

class PriceListController extends AdminPageController
{
    public function index(Request $request)
    {
        $this->authorize('manage_options');

        // $priceList = PriceList::find(3070);

        // $sectionIDs = get_posts([
        //     'post_type' => 'pricelistsection',
        //     'posts_per_page' => -1,
        //     'fields' => 'ids',
        //     'post_parent' => $priceList->id,
        //     'cache_results' => false,
        // ]);

        // dd($sectionIDs);

        return view('pages.pricelist');
    }
}

<?php

namespace Skeleton\Http\Controllers\Api\Pricelist;

use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Request;
use Skeleton\Http\Resources\PriceList\PriceListResource;
use Skeleton\Models\PriceList;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return PriceListResource::collection(PriceList::all());
    }
}

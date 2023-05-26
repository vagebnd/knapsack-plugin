<?php

namespace Skeleton\Http\Controllers\Api\Pricelist;

use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Request;
use Skeleton\Http\Resources\PriceList\PriceListResource;
use Skeleton\Models\PriceList;

class ViewController extends Controller
{
    public function view(Request $request, int $id)
    {
        $pricelist = PriceList::findOrFail($id);

        return PriceListResource::make($pricelist);
    }
}

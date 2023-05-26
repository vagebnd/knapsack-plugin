<?php

namespace Skeleton\Http\Controllers\Api\Pricelist;

use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Request;
use Skeleton\Models\PriceList;

class DeleteController extends Controller
{
    public function delete(Request $request, int $id)
    {
        PriceList::delete($id);
    }
}

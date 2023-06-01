<?php

namespace Skeleton\Blocks\Shortcodes;

use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Support\Collections\Arr;
use Skeleton\Models\PriceList as ModelsPriceList;
use function Skeleton\Support\app;

class Pricelist extends Shortcode
{
    public static $signature = 'pricelist';

    public function render($attrs, $content = null)
    {
        $priceListID = Arr::get($attrs, 'pricelist', null);
        $priceList = ModelsPriceList::find($priceListID);

        return app(ViewContract::class)->render('shortcodes.pricelist', [
            'priceList' => $priceList,
        ]);
    }
}

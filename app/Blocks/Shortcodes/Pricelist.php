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
        $priceListIDs = Arr::wrap(Arr::get($attrs, 'pricelist', []));

        $priceLists = collect($priceListIDs)
            ->map(function ($priceListID) {
                return ModelsPriceList::find($priceListID);
            })
            ->filter()
            ->toArray();

        return app(ViewContract::class)->render('shared.views.shortcodes.pricelist', [
            'priceLists' => $priceLists,
        ]);
    }
}

<?php

namespace Skeleton\Models;

use Knapsack\Compass\Support\Orm\Model;

class PriceListSection extends Model
{
    public function items()
    {
        return $this->hasMany(PriceListItem::class)
            ->orderBy('menu_order')
            ->get();
    }
}

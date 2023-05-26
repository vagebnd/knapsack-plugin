<?php

namespace Skeleton\Models;

use Knapsack\Compass\Support\Orm\Model;

class PriceList extends Model
{
    public static function clean(int $id)
    {
        $sectionIDs = PriceListSection::get([
            'fields' => 'ids',
            'post_parent' => $id,
            'posts_per_page' => -1,
        ]);

        if (! count($sectionIDs)) {
            return;
        }

        $itemIDs = PriceListItem::get([
            'fields' => 'ids',
            'post_parent__in' => $sectionIDs,
            'posts_per_page' => -1,
        ]);

        PriceListItem::delete($itemIDs);
        PriceListSection::delete($sectionIDs);
    }

    public static function delete($id)
    {
        self::clean($id);
        parent::delete($id);
    }

    public function sections()
    {
        return $this->hasMany(PriceListSection::class)
            ->orderBy('menu_order')
            ->get();
    }
}

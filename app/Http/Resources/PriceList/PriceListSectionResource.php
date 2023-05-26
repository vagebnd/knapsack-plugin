<?php

namespace Skeleton\Http\Resources\Pricelist;

use Knapsack\Compass\Support\JsonResource;
use Knapsack\Compass\Support\Request;

/**
 * @mixin \WP_Post
 * @mixin \Skeleton\Models\PriceListSection
 */
class PriceListSectionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->ID,
            'title' => $this->post_title,
            'items' => PriceListItemResource::collection($this->items()),
        ];
    }
}

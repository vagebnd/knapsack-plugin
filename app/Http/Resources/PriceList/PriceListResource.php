<?php

namespace Skeleton\Http\Resources\PriceList;

use Knapsack\Compass\Support\JsonResource;
use Knapsack\Compass\Support\Request;
use Skeleton\Http\Resources\Pricelist\PriceListSectionResource;

/**
 * @mixin \WP_Post
 * @mixin \Skeleton\Models\PriceList
 */
class PriceListResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->ID,
            'title' => $this->post_title,
            'type' => $this->type,
            'sections' => PriceListSectionResource::collection($this->sections()),
        ];
    }
}

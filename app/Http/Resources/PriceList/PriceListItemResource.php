<?php

namespace Skeleton\Http\Resources\Pricelist;

use Knapsack\Compass\Support\JsonResource;
use Knapsack\Compass\Support\Request;

/**
 * @mixin \WP_Post
 * @mixin \Skeleton\Models\PriceListItem
 */
class PriceListItemResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->ID,
            'title' => $this->post_title,
            'content' => $this->post_content,
            'price' => (float) ($this->price),
            'images' => $this->images(),
            'tags' => $this->tags(),
        ];
    }
}

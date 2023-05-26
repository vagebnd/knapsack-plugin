<?php

namespace Skeleton\Http\Resources;

use Knapsack\Compass\Support\JsonResource;
use Knapsack\Compass\Support\Request;

/**
 * @mixin \WP_Term
 */
class TagResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'name' => $this->name,
        ];
    }
}

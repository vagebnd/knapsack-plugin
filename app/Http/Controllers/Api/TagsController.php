<?php

namespace Skeleton\Http\Controllers\Api;

use Knapsack\Compass\Routing\Controller;

class TagsController extends Controller
{
    public function index()
    {
        return collect(get_terms([
                'taxonomy' => 'post_tag',
                'hide_empty' => false,
            ]))
            ->pluck('name')
            ->toArray();
    }
}

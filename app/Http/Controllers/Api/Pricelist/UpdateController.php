<?php

namespace Skeleton\Http\Controllers\Api\Pricelist;

use Illuminate\Support\Arr;
use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Request;
use Skeleton\Models\PriceList;
use Skeleton\Models\PriceListItem;
use Skeleton\Models\PriceListSection;

class UpdateController extends Controller
{
    public function update(Request $request, int $id = null)
    {
        $request->validate([
            'title' => ['min:2', 'max:200'],
            'type' => ['required'],
            'content' => ['min:2', 'max:500'],
            'sections' => ['array'],
            'sections.*.title' => ['required', 'min:2', 'max:200'],
            'sections.*.content' => ['min:2', 'max:500'],
            'sections.*.items' => ['array'],
            'sections.*.items.*.title' => ['required',  'min:2', 'max:200'],
            'sections.*.items.*.content' => ['min:2', 'max:500'],
        ]);

        if (! is_null($id)) {
            $priceList = PriceList::update([
                'ID' => $id,
                'post_title' => $request->input('title'),
                'post_content' => $request->input('content', ''),
                'type' => $request->input('type', ''),
            ]);
        } else {
            $priceList = PriceList::create([
                'post_title' => $request->input('title'),
                'post_content' => $request->input('content', ''),
                'type' => $request->input('type', ''),
            ]);
        }

        PriceList::clean($priceList->ID);

        foreach ($request->input('sections', []) as $sectionIndex => $section) {
            $sectionModel = PriceListSection::create([
                'post_title' => Arr::get($section, 'title'),
                'post_content' => Arr::get($section, 'content', ''),
                'post_parent' => $priceList->ID,
                'menu_order' => $sectionIndex,
            ]);

            foreach (Arr::get($section, 'items', []) as $itemIndex => $item) {
                $images = collect(Arr::get($item, 'images', []))
                    ->pluck('id')
                    ->toArray();

                $priceListItem = PriceListItem::create([
                    'post_title' => Arr::get($item, 'title'),
                    'post_content' => Arr::get($item, 'content', ''),
                    'post_parent' => $sectionModel->ID,
                    'menu_order' => $itemIndex,
                    'price' => Arr::get($item, 'price', 8),
                    'images' => $images,
                ]);

                $priceListItem->addTags(Arr::get($item, 'tags', []));
            }
        }

        return [
            'id' => $priceList->ID,
        ];
    }
}

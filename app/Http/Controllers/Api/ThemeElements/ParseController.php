<?php

namespace Skeleton\Http\Controllers\Api\ThemeElements;

use Exception;
use Knapsack\Compass\Routing\Controller;
use Knapsack\Compass\Support\Request;

class ParseController extends Controller
{
    public function parse(Request $request)
    {
        $request->validate([
            'data' => ['required', 'min:10'],
        ]);

        $data = $request->input('data');
        $imagePlaceholder = get_page_by_title('image-placeholder', OBJECT, 'attachment');

        if (empty($imagePlaceholder)) {
            throw new Exception('Theme element image placeholder not found.');
        }

        $data = str_replace('{{ host }}', get_site_url(), $data);
        $data = str_replace('{{ imagePlaceholder }}', $imagePlaceholder->guid, $data);

        return $data;
    }
}

<?php

namespace Skeleton\Enums;

use Exception;
use Knapsack\Compass\Support\Enum;
use Knapsack\Compass\Support\Facades\Config;

class PriceListType extends Enum
{
    public const SLIDER = 'slider';
    public const ROWS = 'rows';
    public const COLUMNS = 'columns';

    public static function getDescription($value): string
    {
        $textDomain = Config::get('app.text_domain');

        $map = [
            self::SLIDER => __('slider', $textDomain),
            self::ROWS => __('rows', $textDomain),
            self::COLUMNS => __('columns', $textDomain),
        ];

        if (! array_key_exists($value, $map)) {
            throw new Exception("Invalid value for PriceListType: {$value}");
        }

        return $map[$value];
    }
}

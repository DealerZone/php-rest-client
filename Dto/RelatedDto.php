<?php

namespace DealerInventory\Client\Dto;

/**
 * @property-read string slug
 * @property-read string name
 * @property-read string stock_number
 * @property-read string url
 * @property-read string price
 * @property-read string status
 * @property-read boolean is_on_sale
 * @property-read boolean is_featured
 * @property-read boolean is_clean
 * @property-read boolean is_junk
 * @property-read boolean is_sold
 * @property-read boolean is_on_hold
 * @property-read boolean is_repaired
 * @property-read ImageDto main_image
 * @property-read LocationDto location
 */
class RelatedDto extends Base
{
    protected $casts = [
        'main_image' => ImageDto::class,
    ];
}

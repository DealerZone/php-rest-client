<?php

namespace DealerInventory\Client\Dto;

use Illuminate\Support\Collection;

/**
 * @property-read string slug
 * @property-read string name
 * @property-read string description
 * @property-read string sku
 * @property-read string price
 * @property-read string oem_part_number
 * @property-read ImageDto main_image
 * @property-read ImageDto[]|Collection images
 */
class AutoPartDto extends Base
{

}

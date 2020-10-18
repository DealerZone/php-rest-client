<?php

namespace DealerInventory\Client\Dto;

use Tightenco\Collect\Support\Collection;

/**
 * @property-read string key
 * @property-read string name
 * @property-read string domain
 * @property-read string sales_email
 * @property-read string paypal_email
 * @property-read string sales_phone_number
 * @property-read string fax_number
 * @property-read Collection|LocationDto[] locations
 */
class InfoDto extends Base
{

}

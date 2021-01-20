<?php

namespace DealerInventory\Client\Dto;

use App\Services\Rentals\Models\RentalImage;
use Illuminate\Support\Collection;

/**
 * @property string slug
 * @property string name
 * @property string description_html
 * @property string price_per_day
 * @property integer passengers
 * @property integer luggages how many luggage bags does it fit
 * @property integer doors
 * @property string trans_type automatic or manual transmission
 * @property string status
 * @property string vin
 * @property integer year
 * @property string make
 * @property string model
 * @property string trim
 * @property string style
 * @property string market_class
 * @property string vehicle_type
 * @property string body
 * @property string drivetrain
 * @property string engine
 * @property string fuel_type
 * @property string chrome_style_id
 * @property Collection|RentalImage[] images
 * @property RentalImage main_image
 */
class RentalDto extends Base
{

}

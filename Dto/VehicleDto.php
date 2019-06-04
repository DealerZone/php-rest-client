<?php

namespace DealerInventory\Client\Dto;

/**
 * @property-read string slug
 * @property-read string name
 * @property-read string stock_number
 * @property-read string price
 * @property-read string vin
 * @property-read integer year
 * @property-read string make
 * @property-read string model
 * @property-read string trim
 * @property-read string style
 * @property-read string body
 * @property-read string drivetrain
 * @property-read string doors
 * @property-read string engine
 * @property-read string horsepower
 * @property-read string torque_rating
 * @property-read string engine_cylinders
 * @property-read string engine_displacement
 * @property-read string transmission
 * @property-read string interior_color
 * @property-read string exterior_color
 * @property-read string fuel_economy
 * @property-read string fuel_type
 * @property-read string msrp
 * @property-read string title_status
 * @property-read string description
 * @property-read string damage_type
 * @property-read string secondary_damage_type
 * @property-read string airbag_status
 * @property-read boolean airbag_deployed
 * @property-read string mileage
 * @property-read string status
 * @property-read boolean is_on_sale
 * @property-read boolean is_featured
 * @property-read boolean is_clean
 * @property-read boolean is_junk
 * @property-read boolean is_sold
 * @property-read boolean is_on_hold
 * @property-read boolean is_repaired
 * @property-read array installed_options
 * @property-read LocationDto location
 * @property-read ImageDto main_image
 * @property-read ImageDto[] images
 */
class VehicleDto extends Dto
{

}

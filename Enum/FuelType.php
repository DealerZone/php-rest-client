<?php

namespace DealerInventory\Client\Enum;

use DealerInventory\Common\Enum\Enum;

class FuelType extends Enum
{
    const GAS = 'Gasoline Fuel';
    const DIESEL = 'Diesel Fuel';
    const HYBRID = 'Gas/Electric Hybrid';
    const ELECTRIC = 'Electric Fuel System';
    const NATURAL_GAS = 'Natural Gas Fuel';
    const PLUGIN_FLEX_GAS = 'Plug-In Electric/Gas';
    const FLEX = 'Flex Fuel Capability';
    const GAS_PROPANE = 'Gasoline/Propane';
    const GAS_NATURAL_GAS = 'Gasoline/Natural Gas';
    const FLEX_ELEXTRIC = 'Flex Fuel/Electric Hybrid';
    const FLEX_ELECTRIC_HYBRID = 'Flex Fuel/Electric Hybrid';
    const PROPANE = 'Propane Fuel';
    const PLUGIN_FLEX = 'Plug-In Electric/Flex Fuel';
    const HYDROGEN = 'Hydrogen Fuel';
}

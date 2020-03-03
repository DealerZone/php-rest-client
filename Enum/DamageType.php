<?php

namespace DealerInventory\Client\Enum;

use DealerInventory\Common\Enum\Enum;

class DamageType extends Enum
{
    const COLLISION = 'Collision';
    const THEFT = 'Theft';
    const FIRE = 'Fire';
    const FLOOD = 'Flood';
    const REPO = 'Repo';
    const VANDALISM = 'Vandalism';
    const MECHANICAL = 'Mechanical';
    const NO_DAMAGE = 'No Damage';
    const UNDERCARRIAGE = 'Undercarriage';
    const HAIL = 'Hail';
    const MINOR = 'Minor dents and scratches';
    const FRAME = 'Frame';
    const WATER = 'Water Recovery';
}

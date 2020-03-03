<?php

namespace DealerInventory\Client\Enum;

use DealerInventory\Common\Enum\Enum;

class AirbagStatus extends Enum
{
    const NA = 'N/A';
    const GOOD = 'Good';
    const DEPLOYED = 'Deployed';
    const MISSING = 'Missing';
    const GOOD_FRONT = 'Good (Front)';
}

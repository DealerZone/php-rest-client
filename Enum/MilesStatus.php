<?php

namespace DealerInventory\Client\Enum;

use DealerInventory\Common\Enum\Enum;

class MilesStatus extends Enum
{
    const ACTUAL = 'Actual';
    const TMU = 'TMU';
    const NOT_ACTUAL = 'Not Actual';
    const EXEMPT = 'Exempt';
    const NOT_READABLE = 'Not Readable';
}

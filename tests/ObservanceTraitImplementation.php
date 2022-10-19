<?php
/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\ObservanceInterface;
use Org_Heigl\Holidaychecker\ObservanceTrait;

final class ObservanceTraitImplementation implements ObservanceInterface
{
    use ObservanceTrait;
}

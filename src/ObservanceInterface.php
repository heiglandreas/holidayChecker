<?php
/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\Holidaychecker;

interface ObservanceInterface
{
    public function setObservances(?int $firstObservance = null, ?int $lastObservance = null): void;

    public function isWithinObservance(int $gregorianYear): bool;
}

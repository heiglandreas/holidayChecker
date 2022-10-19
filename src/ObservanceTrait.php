<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\Holidaychecker;

trait ObservanceTrait
{
    /** @var int|null */
    private $firstObservance = null;

    /** @var int|null */
    private $lastObservance = null;

    public function setObservances(?int $firstObservance = null, ?int $lastObservance = null): void
    {
        $this->firstObservance = $firstObservance;
        $this->lastObservance = $lastObservance;
    }

    public function isWithinObservance(int $gregorianYear): bool
    {
        if (null !== $this->firstObservance && $this->firstObservance > $gregorianYear) {
            return false;
        }

        if (null !== $this->lastObservance && $this->lastObservance < $gregorianYear) {
            return false;
        }

        return true;
    }
}

<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\Holidaychecker\Factory;

use DOMElement;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;

interface ItemFromDomElementCreator
{
	public function itemFromDomElement(DOMElement $element): ?HolidayIteratorItemInterface;
}

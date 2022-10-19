<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker\Factory;

use DOMElement;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\IteratorItem\Easter;

class EasterFactory implements ItemFromDomElementCreator
{
	public function itemFromDomElement(DOMElement $element): ?HolidayIteratorItemInterface
	{
		if ($element->nodeName !== 'easter') {
			return null;
		}

		return new Easter(
			$element->textContent,
			$element->getAttribute('free') === "true",
			(int) $element->getAttribute('offset')
		);
	}
}

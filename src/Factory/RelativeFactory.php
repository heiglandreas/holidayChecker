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
use Org_Heigl\Holidaychecker\IteratorItem\Relative;

class RelativeFactory implements ItemFromDomElementCreator
{
	public function itemFromDomElement(DOMElement $element): ?HolidayIteratorItemInterface
	{
		if ($element->nodeName !== 'relative') {
			return null;
		}

		return new Relative(
			$element->textContent,
			$element->getAttribute('free') === "true",
			(int) $element->getAttribute('day'),
			(int) $element->getAttribute('month'),
			$element->getAttribute('relation')
		);
	}
}

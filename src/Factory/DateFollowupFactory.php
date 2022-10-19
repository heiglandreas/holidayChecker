<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker\Factory;

use DOMElement;
use Org_Heigl\Holidaychecker\CalendarDayFactory;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp;
use function explode;

class DateFollowupFactory implements ItemFromDomElementCreator
{
	public function itemFromDomElement(DOMElement $element): ?HolidayIteratorItemInterface
	{
		if ($element->nodeName !== 'dateFollowUp') {
			return null;
		}

		$day = CalendarDayFactory::createCalendarDay(
			(int) $element->getAttribute('day'),
			(int) $element->getAttribute('month'),
			($element->hasAttribute('calendar') ? $element->getAttribute('calendar') : 'gregorian')
		);

		return new DateFollowUp(
			$element->textContent,
			$element->getAttribute('free') === "true",
			$day,
			$element->getAttribute('followup'),
			($element->hasAttribute('replaced') ? explode(' ', $element->getAttribute('replaced')) : [])
		);
	}
}

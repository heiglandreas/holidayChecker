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
use Org_Heigl\Holidaychecker\IteratorItem\Date;
use function explode;

class DateFactory implements ItemFromDomElementCreator
{
	public function itemFromDomElement(DOMElement $element): ?HolidayIteratorItemInterface
	{
		if ($element->nodeName !== 'date') {
			return null;
		}

		$day = CalendarDayFactory::createCalendarDay(
			(int) $element->getAttribute('day'),
			(int) $element->getAttribute('month'),
			($element->hasAttribute('calendar') ? $element->getAttribute('calendar') : 'gregorian')
		);

		if ($element->hasAttribute('year')) {
			$day->setYear((int) $element->getAttribute('year'));
		}

		return new Date(
			$element->textContent,
            $element->getAttribute('free') === "true",
			$day,
			$element->hasAttribute('forwardto') ? $element->getAttribute('forwardto') : '',
			$element->hasAttribute('forwardwhen') ? explode(' ', $element->getAttribute('forwardwhen')) : [],
			$element->hasAttribute('rewindto') ? $element->getAttribute('rewindto') : '',
			$element->hasAttribute('rewindwhen') ? explode(' ', $element->getAttribute('rewindwhen')) : [],
		);
	}
}

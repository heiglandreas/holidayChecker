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
use Org_Heigl\Holidaychecker\GregorianWeekday;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\IteratorItem\SwapDecorator;
use Org_Heigl\Holidaychecker\SwapDirection;
use Org_Heigl\Holidaychecker\SwapRule;
use function array_map;
use function explode;

final class SwapDecoratorFactory implements DecorateFromDomElement
{
	public function decorate(HolidayIteratorItemInterface $element, DOMElement $domElement): HolidayIteratorItemInterface
	{
		$rules = [];

		if ($domElement->hasAttribute('forwardto') && $domElement->hasAttribute('forwardwhen')) {
			$rules[] = $this->createRuleFrom($domElement->getAttribute('forwardto'), $domElement->getAttribute('forwardwhen'), SwapDirection::forward());
		}
		if ($domElement->hasAttribute('alternateforwardto') && $domElement->hasAttribute('alternateforwardwhen')) {
			$rules[] = $this->createRuleFrom($domElement->getAttribute('alternateforwardto'), $domElement->getAttribute('alternateforwardwhen'), SwapDirection::forward());
		}
		if ($domElement->hasAttribute('rewindto') && $domElement->hasAttribute('rewindwhen')) {
			$rules[] = $this->createRuleFrom($domElement->getAttribute('rewindto'), $domElement->getAttribute('rewindwhen'), SwapDirection::rewind());
		}
		if ($domElement->hasAttribute('alternaterewindto') && $domElement->hasAttribute('alternaterewindwhen')) {
			$rules[] = $this->createRuleFrom($domElement->getAttribute('alternaterewindto'), $domElement->getAttribute('alternaterewindwhen'), SwapDirection::rewind());
		}

		if ($rules === []) {
			return $element;
		}

		$day = CalendarDayFactory::createCalendarDay(
			(int) $domElement->getAttribute('day'),
			(int) $domElement->getAttribute('month'),
			($domElement->hasAttribute('calendar') ? $domElement->getAttribute('calendar') : 'gregorian')
		);

		if ($domElement->hasAttribute('year')) {
			$day->setYear((int) $domElement->getAttribute('year'));
		}

		return new SwapDecorator($element, $day, ...$rules);
	}

	private function createRuleFrom(string $to, string $when, SwapDirection $direction): SwapRule
	{
		return new SwapRule(
			$direction,
			GregorianWeekday::fromString($to),
			...array_map(function ($item) {
				return GregorianWeekday::fromString($item);
			}, explode(' ', $when))
		);
	}
}

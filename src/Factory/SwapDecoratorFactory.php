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
		$rules = $this->getRulesFromDomElement($domElement);

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

	/**
	 * @return SwapRule[]
	 */
	private function getRulesFromDomElement(DOMElement $domElement): array
	{
		$attributes = [
			'forward' => SwapDirection::forward(),
			'alternateforward' => SwapDirection::forward(),
			'rewind' => SwapDirection::rewind(),
			'alternaterewind' => SwapDirection::rewind(),
		];

		$rules = [];
		foreach ($attributes as $attribute => $direction) {
			if ($domElement->hasAttribute($attribute . 'to') && $domElement->hasAttribute($attribute . 'when')) {
				$rules[] = $this->createRuleFrom($domElement->getAttribute($attribute . 'to'), $domElement->getAttribute($attribute . 'when'), $direction);
			}
		}

		return $rules;
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

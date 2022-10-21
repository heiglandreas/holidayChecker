<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker\IteratorItem;

use DateTimeInterface;
use IntlCalendar;
use Org_Heigl\Holidaychecker\CalendarDay;
use Org_Heigl\Holidaychecker\GregorianWeekday;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\SwapDirection;
use Org_Heigl\Holidaychecker\SwapRule;
use function in_array;

class SwapDecorator implements HolidayIteratorItemInterface
{
	/** @var HolidayIteratorItemInterface  */
	private $rule;

	/** @var CalendarDay  */
	private $day;

	/** @var SwapRule[]  */
	private $swapRules;

	public function __construct(HolidayIteratorItemInterface $rule, CalendarDay $day, SwapRule ...$swapRules)
	{
		$this->rule = $rule;
		$this->day = $day;
		$this->swapRules = $swapRules;
	}

	public function dateMatches(DateTimeInterface $date): bool
	{
		$year = (int) $date->format('Y');
		$weekday = GregorianWeekday::fromIntlWeekday($this->day->getWeekdayForGregorianYear($year));
		foreach ($this->swapRules as $rule) {
			if ($this->ruleMatches($rule, $weekday)) {
				return $this->isModifiedDate($date, $rule->getSwapToDay(), $rule->getDirection());
			}
		}

		return $this->rule->dateMatches($date);
	}

	public function getName(): string
	{
		return $this->rule->getName();
	}

	public function isHoliday(): bool
	{
		return $this->rule->isHoliday();
	}

	private function ruleMatches(SwapRule $rule, GregorianWeekday $weekday): bool
	{
		return in_array($weekday, $rule->getSwapWhenDays(), true);
	}

	private function isModifiedDate(DateTimeInterface $dateTime, GregorianWeekday $modifiedDay, SwapDirection $direction): bool
	{
		$cal = $this->day->getCalendar();
		$cal = CalendarDay::setGregorianYearForDate((int) $dateTime->format('Y'), $cal);
		$day = $cal->toDateTime();
		$day->modify($direction->getDateTimeDirection() . ' ' . $modifiedDay);
		$cal->setTime($day->getTimestamp() * 1000);
		$cal2 = $this->day->getCalendar();
		$cal2->setTime($dateTime->getTimestamp() * 1000);

		if ($this->day->hasYearSet() && $cal->get(IntlCalendar::FIELD_YEAR) !== $cal2->get(IntlCalendar::FIELD_YEAR)) {
			return false;
		}

		if ($cal->get(IntlCalendar::FIELD_MONTH) !== $cal2->get(IntlCalendar::FIELD_MONTH)) {
			return false;
		}

		return $cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) === $cal2->get(IntlCalendar::FIELD_DAY_OF_MONTH);
	}
}

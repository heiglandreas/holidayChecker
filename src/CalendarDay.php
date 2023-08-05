<?php

declare(strict_types=1);

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\Holidaychecker;

use DateTimeInterface;
use IntlCalendar;

class CalendarDay
{
	/** @var int */
	private $day;

	/** @var int */
	private $month;

	/** @var int|null */
	private $year = null;

	/** @var IntlCalendar */
	private $calendar;

	public function __construct(int $day, int $month, IntlCalendar $calendar)
	{
		$this->day = $day;
		$this->month = $month;
		$this->calendar = $calendar;
		$this->calendar->set(IntlCalendar::FIELD_DAY_OF_MONTH, $day);
		$this->calendar->set(IntlCalendar::FIELD_MONTH, $month - 1);
		$this->calendar->set(IntlCalendar::FIELD_HOUR_OF_DAY, 12);
		$this->calendar->set(IntlCalendar::FIELD_MINUTE, 0);
		$this->calendar->set(IntlCalendar::FIELD_SECOND, 0);
		$this->calendar->set(IntlCalendar::FIELD_MILLISECOND, 0);
	}

	public function setYear(int $year): void
	{
		$this->year = $year;
		$this->calendar->set(IntlCalendar::FIELD_YEAR, $year);
	}

	public function setGregorianYear(int $year): void
	{
		$calendarYear = (int) $this->calendar->toDateTime()->format('Y');

		$diff = $year - $calendarYear;
		$realYear = $this->calendar->get(IntlCalendar::FIELD_YEAR);

		$this->year = $realYear + $diff;
		$this->calendar->add(IntlCalendar::FIELD_YEAR, $diff);
	}

	public function isSameDay(DateTimeInterface $dateTime): bool
	{
		$cal = clone $this->calendar;
		$cal->setTime($dateTime->getTimestamp() * 1000);

		if (null !== $this->year &&
			$cal->get(IntlCalendar::FIELD_YEAR) !== $this->calendar->get(IntlCalendar::FIELD_YEAR)
		) {
			return false;
		}

		if ($cal->get(IntlCalendar::FIELD_MONTH) !== $this->calendar->get(IntlCalendar::FIELD_MONTH)) {
			return false;
		}

		return $cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) === $this->calendar->get(IntlCalendar::FIELD_DAY_OF_MONTH);
	}

	public function getCalendar(): IntlCalendar
	{
		return clone $this->calendar;
	}

	public function hasYearSet(): bool
	{
		return null !== $this->year;
	}

	public function isFollowUpDay(DateTimeInterface $dateTime, string $followUpDay): bool
	{
		return $this->isModifiedDate($dateTime, $followUpDay, 'next');
	}

	private function isModifiedDate(DateTimeInterface $dateTime, string $modifiedDay, string $direction): bool
	{
		$cal = clone $this->calendar;
		$cal = self::setGregorianYearForDate((int) $dateTime->format('Y'), $cal);
		$day = $cal->toDateTime();
		$day->modify($direction . ' ' . $modifiedDay);
		$cal->setTime($day->getTimestamp() * 1000);
		$cal2 = clone $this->calendar;
		$cal2->setTime($dateTime->getTimestamp() * 1000);

		if (null !== $this->year && $cal->get(IntlCalendar::FIELD_YEAR) !== $cal2->get(IntlCalendar::FIELD_YEAR)) {
			return false;
		}

		if ($cal->get(IntlCalendar::FIELD_MONTH) !== $cal2->get(IntlCalendar::FIELD_MONTH)) {
			return false;
		}

		return $cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) === $cal2->get(IntlCalendar::FIELD_DAY_OF_MONTH);
	}

	public static function setGregorianYearForDate(int $year, IntlCalendar $calendar): IntlCalendar
	{
		$datetime = $calendar->toDateTime();
		$yearDiff = $year - (int) $datetime->format('Y');

		$calendar->set(IntlCalendar::FIELD_YEAR, $calendar->get(IntlCalendar::FIELD_YEAR) + $yearDiff);
		if ($calendar->toDateTime()->format('Y') < $year) {
			$calendar->set(IntlCalendar::FIELD_YEAR, $calendar->get(IntlCalendar::FIELD_YEAR) + 1);
		}
		if ($calendar->toDateTime()->format('Y') > $year) {
			$calendar->set(IntlCalendar::FIELD_YEAR, $calendar->get(IntlCalendar::FIELD_YEAR) - 1);
		}

		return $calendar;
	}

	public function getWeekdayForGregorianYear(int $year): int
	{
		$cal = $this->getDayForGregorianYear($year);

		return $cal->get(IntlCalendar::FIELD_DAY_OF_WEEK);
	}

	private function getDayForGregorianYear(int $gregorianYear): IntlCalendar
	{
		$cal = clone $this->calendar;
		$cal->set(IntlCalendar::FIELD_MONTH, $this->month - 1);
		$cal->set(IntlCalendar::FIELD_DAY_OF_MONTH, $this->day);

		return self::setGregorianYearForDate($gregorianYear, $cal);
	}
}

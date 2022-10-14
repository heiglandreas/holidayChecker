<?php

declare(strict_types=1);

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     20.02.2018
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
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
    private $year;

    /** @var IntlCalendar */
    private $calendar;

    public function __construct(int $day, int $month, IntlCalendar $calendar)
    {
        $this->day      = $day;
        $this->month    = $month;
        $this->year     = null;
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

    public function isFollowUpDay(DateTimeInterface $dateTime, string $followUpDay): bool
    {
        $cal = clone $this->calendar;
        $cal = self::setGregorianYearForDate((int) $dateTime->format('Y'), $cal);
        $day = $cal->toDateTime();
        $day->modify('next ' . $followUpDay);
        $cal->setTime($day->getTimestamp() * 1000);
        $cal2         = clone $this->calendar;
        $cal2->setTime($dateTime->getTimestamp() * 1000);

        if (null !== $this->year && $cal->get(IntlCalendar::FIELD_YEAR) !== $cal2->get(IntlCalendar::FIELD_YEAR)) {
            return false;
        }

        if ($cal->get(IntlCalendar::FIELD_MONTH) !== $cal2->get(IntlCalendar::FIELD_MONTH)) {
            return false;
        }

        return $cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) === $cal2->get(IntlCalendar::FIELD_DAY_OF_MONTH);
    }

    public function isPreviousDay(DateTimeInterface $dateTime, string $previousDay): bool
    {
        $cal = clone $this->calendar;
        $cal = self::setGregorianYearForDate((int) $dateTime->format('Y'), $cal);
        $day = $cal->toDateTime();
        $day->modify('previous ' . $previousDay);
        $cal->setTime($day->getTimestamp() * 1000);
        $cal2         = clone $this->calendar;
        $cal2->setTime($dateTime->getTimestamp() * 1000);

        if (null !== $this->year && $cal->get(IntlCalendar::FIELD_YEAR) !== $cal2->get(IntlCalendar::FIELD_YEAR)) {
            return false;
        }

        if ($cal->get(IntlCalendar::FIELD_MONTH) !== $cal2->get(IntlCalendar::FIELD_MONTH)) {
            return false;
        }

        return $cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) === $cal2->get(IntlCalendar::FIELD_DAY_OF_MONTH);
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

        $cal = self::setGregorianYearForDate($gregorianYear, $cal);

        return $cal;
    }
}

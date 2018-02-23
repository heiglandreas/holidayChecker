<?php
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
    private $day;

    private $month;

    private $year;

    private $calendar;

    public function __construct(int $day, int $month, IntlCalendar $calendar)
    {
        $this->day      = $day;
        $this->month    = $month;
        $this->year     = null;
        $this->calendar = $calendar;
        $this->calendar->set(IntlCalendar::FIELD_HOUR_OF_DAY, 12);
        $this->calendar->set(IntlCalendar::FIELD_MINUTE, 0);
        $this->calendar->set(IntlCalendar::FIELD_SECOND, 0);
        $this->calendar->set(IntlCalendar::FIELD_MILLISECOND, 0);
    }

    public function setYear(int $year)
    {
        $this->year = $year;
    }

    public function isSameDay(DateTimeInterface $dateTime) : bool
    {
        $cal         = clone $this->calendar;
        $calDateTime = $cal->toDateTime();
        $diff        = $dateTime->diff($calDateTime);


        // Add one day due to time-offset
        $days = $diff->days + 1;
        if ($dateTime < $calDateTime) {
            $days = ($days - 1) * -1;
        }

        $cal->add(IntlCalendar::FIELD_DAY_OF_MONTH, $days);

        if (null !== $this->year && $cal->get(IntlCalendar::FIELD_YEAR) !== $this->year) {
            return false;
        }

        if ($cal->get(IntlCalendar::FIELD_MONTH) !== ($this->month - 1)) {
            return false;
        }

        if ($cal->get(IntlCalendar::FIELD_DAY_OF_MONTH) !== $this->day) {
            return false;
        }

        return true;
    }
}

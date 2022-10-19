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
 * @since     08.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\Holidaychecker\IteratorItem;

use DateTimeInterface;
use IntlCalendar;
use Org_Heigl\Holidaychecker\CalendarDay;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\ObservanceInterface;
use Org_Heigl\Holidaychecker\ObservanceTrait;
use function array_map;
use function in_array;

class DateFollowUp implements HolidayIteratorItemInterface, ObservanceInterface
{
    use ObservanceTrait;

    /** @var CalendarDay */
    private $day;

    /** @var bool */
    private $holiday;

    /** @var string */
    private $name;

    /** @var string */
    private $followup;

    /** @var array */
    private $replaced;

    public function __construct(string $name, bool $holiday, CalendarDay $day, string $followup, array $replaced = [])
    {
        $this->day = $day;
        $this->followup = $followup;
        $this->holiday = $holiday;
        $this->name = $name;
        $this->replaced = $this->replacedDays($replaced);
    }

    public function dateMatches(DateTimeInterface $date): bool
    {
        $gregorianYear = (int) $date->format('Y');
        if (! $this->isWithinObservance($gregorianYear)) {
            return false;
        }

        $weekday = $this->day->getWeekdayForGregorianYear($gregorianYear);

        if (in_array($weekday, $this->replaced)) {
            return $this->day->isFollowUpDay($date, $this->followup);
        }

        return $this->day->isSameDay($date);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isHoliday(): bool
    {
        return $this->holiday;
    }

    private static function replacedDays(array $replaced): array
    {
        $daymap = [
            'sunday' => IntlCalendar::DOW_SUNDAY,
            'monday' => IntlCalendar::DOW_MONDAY,
            'tuesday' => IntlCalendar::DOW_TUESDAY,
            'wednesday' => IntlCalendar::DOW_WEDNESDAY,
            'thursday' => IntlCalendar::DOW_THURSDAY,
            'friday' => IntlCalendar::DOW_FRIDAY,
            'saturday' => IntlCalendar::DOW_SATURDAY,
        ];

        if ([] === $replaced) {
            return [
                IntlCalendar::DOW_SATURDAY,
                IntlCalendar::DOW_SUNDAY,
            ];
        }

        return array_map(function (string $day) use ($daymap) {
            if (! isset($daymap[$day])) {
                return null;
            }
            return $daymap[$day];
        }, $replaced);
    }
}

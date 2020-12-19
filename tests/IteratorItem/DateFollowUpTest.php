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

namespace Org_Heigl\HolidaycheckerTest\IteratorItem;

use DateTimeImmutable;
use Org_Heigl\Holidaychecker\Calendar;
use Org_Heigl\Holidaychecker\CalendarDayFactory;
use Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp;
use PHPUnit\Framework\TestCase;

class DateFollowUpTest extends TestCase
{
    /**
     * @dataProvider dateProvider
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::replacedDays
     */
    public function testThatDateFollowupTestWorks(
        $dateTime,
        $day,
        $month,
        $followup,
        $replaced,
        $result,
        $name,
        $isHoliday
    ) {
        $calendarDate = CalendarDayFactory::createCalendarDay($day, $month, Calendar::GREGORIAN);

        $followUp = new DateFollowUp($name, $isHoliday, $calendarDate, $followup, $replaced);
        $this->assertEquals($result, $followUp->dateMatches($dateTime));
        $this->assertEquals($name, $followUp->getName());
        $this->assertEquals($isHoliday, $followUp->isHoliday());
    }

    public function dateProvider()
    {
        return [
            [new DateTimeImmutable('2018-03-01 12:00:00+00:00'), 25, 2, 'thursday', [], true, 'test', true],
            [new DateTimeImmutable('2018-02-26 12:00:00+00:00'), 24, 2, 'monday', [], true, 'test', true],
   //         [new \DateTimeImmutable('2019-01-01 12:00:00+00:00'), 30, 12, 'tuesday', [], true, 'test', true],
            [
                new DateTimeImmutable('2019-10-12 12:00:00+00:00'),
                11,
                10,
                'saturday',
                ['thursday', 'friday'],
                true,
                'test',
                true,
            ], [
                new DateTimeImmutable('2019-10-12 12:00:00+00:00'),
                10,
                10,
                'saturday',
                ['thursday', 'friday'],
                true,
                'test',
                true,
            ], [
                new DateTimeImmutable('2019-10-09 12:00:00+00:00'),
                9,
                10,
                'saturday',
                ['thursday', 'friday'],
                true,
                'test',
                true,
            ],
        ];
    }
}

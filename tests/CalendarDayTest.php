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
 * @since     21.02.2018
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\Calendar;
use Org_Heigl\Holidaychecker\CalendarDay;
use PHPUnit\Framework\TestCase;
use IntlCalendar;
use DateTimeImmutable;
use Mockery as M;

class CalendarDayTest extends TestCase
{
    public function testConstructorCalendar()
    {
        $calendar = M::mock(IntlCalendar::class);
        $calendar->shouldReceive('set')->times(4);

        $class = new CalendarDay(2, 3, $calendar);

        self::assertInstanceOf(CalendarDay::class, $class);
        self::assertAttributeEquals(2, 'day', $class);
        self::assertAttributeEquals(3, 'month', $class);
        self::assertAttributeEquals($calendar, 'calendar', $class);
    }

    /** @dataProvider theSameDayWithoutCheckingYearProvider */
    public function testIsSameDay($day, $month, $year, $calendar, $compare, $result)
    {
        $cal = IntlCalendar::createInstance(null, '@calendar=' . $calendar);

        $class = new CalendarDay($day, $month, $cal);
        if (null !== $year) {
            $class->setYear($year);
        }

        $this->assertSame($result, $class->isSameDay($compare));
    }

    public function theSameDayWithoutCheckingYearProvider()
    {
        return [
            'Month doesnt match' => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('1.2.2000'), false],
            'Day doesnt match'   => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('2.1.2000'), false],
            'Matches'            => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('1.1.2000'), true],
            'Matches'            => [1, 1, 2017, Calendar::GREGORIAN, new DateTimeImmutable('1.1.2000'), false],
            'Rosh Hashana 5779'  => [1, 1, null, Calendar::HEBREW, new DateTimeImmutable('10.9.2018'), true],
            'Rosh Hashana 5778'  => [1, 1, null, Calendar::HEBREW, new DateTimeImmutable('21.9.2017'), true],
            // This should be the 15th of june according to https://en.wikipedia.org/wiki/Eid_al-Fitr
            'Eid al-Fitr 1439'   => [1, 10, null, Calendar::ISLAMIC, new DateTimeImmutable('14.6.2018'), true],
        ];
    }
}

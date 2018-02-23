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
 * @since     08.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest\IteratorItem;

use Org_Heigl\Holidaychecker\Calendar;
use Org_Heigl\Holidaychecker\CalendarDayFactory;
use Org_Heigl\Holidaychecker\IteratorItem\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /**
     * @dataProvider dateProvider
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::dateMatches
     */
    public function testThatDateTestWorks($dateTime, $day, $month, $year, $result, $name, $isHoliday)
    {
        $calendarDate = CalendarDayFactory::createCalendarDay($day, $month, Calendar::GREGORIAN);
        if ($year) {
            $calendarDate->setYear($year);
        }
        $easter = new Date($name, $isHoliday, $calendarDate);
        $this->assertEquals($result, $easter->dateMatches($dateTime));
        $this->assertEquals($name, $easter->getName());
        $this->assertEquals($isHoliday, $easter->isHoliday());
    }

    public function dateProvider()
    {
        return [
            [new \DateTime('2017-12-24 12:00:00+00:00'), 24, 12, null, true, 'test', true],
            [new \DateTime('2017-12-25 12:00:00+00:00'), 24, 12, null, false, 'test', true],
            [new \DateTime('2017-04-17 12:00:00+00:00'), 17, 04, 2017, true, 'test', false],
            [new \DateTime('2016-04-17 12:00:00+00:00'), 17, 04, 2017, false, 'test', true],
        ];
    }
}

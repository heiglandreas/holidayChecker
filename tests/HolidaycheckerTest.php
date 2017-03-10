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
 * @since     09.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\Holidaychecker;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use PHPUnit\Framework\TestCase;

class HolidaycheckerTest extends TestCase
{
    /**
     * @dataProvider integrationProvider
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::createIteratorFromXmlFile
     * @covers \Org_Heigl\Holidaychecker\Holidaychecker::__construct
     * @covers \Org_Heigl\Holidaychecker\Holidaychecker::check
     * @covers \Org_Heigl\Holidaychecker\Holiday::isHoliday
     * @covers \Org_Heigl\Holidaychecker\Holiday::isNamed
     * @covers \Org_Heigl\Holidaychecker\Holiday::getName
     * @covers \Org_Heigl\Holidaychecker\Holiday::__construct
     * @covers \Org_Heigl\Holidaychecker\HolidayIterator::append
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getElement
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getFree
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getEaster
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::__construct
     */
    public function testIntegration($date, $holiday, $named, $name)
    {
        $factory = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromXmlFile(__DIR__ . '/../share/DE-HE.xml');
        $checker = new Holidaychecker($iterator);

        $result = $checker->check($date);
        $this->assertEquals($holiday, $result->isHoliday());
        $this->assertEquals($named, $result->isNamed());
        $this->assertEquals($name, $result->getName());
    }

    public function integrationProvider()
    {
        return [
            [new \DateTime('2017-04-16'), false, true, 'Ostersonntag'],
            [new \DateTime('2017-04-17'), true, true, 'Ostermontag'],
        ];
    }

    /**
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::createIteratorFromXmlFile
     * @covers \Org_Heigl\Holidaychecker\Holidaychecker::__construct
     * @covers \Org_Heigl\Holidaychecker\Holidaychecker::check
     * @covers \Org_Heigl\Holidaychecker\Holiday::isHoliday
     * @covers \Org_Heigl\Holidaychecker\Holiday::isNamed
     * @covers \Org_Heigl\Holidaychecker\Holiday::getName
     * @covers \Org_Heigl\Holidaychecker\Holiday::__construct
     * @covers \Org_Heigl\Holidaychecker\HolidayIterator::append
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getElement
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getFree
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getEaster
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::isHoliday
     */
    public function testSpeed()
    {
        $factory = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromXmlFile(__DIR__ . '/../share/DE-HE.xml');
        $checker = new Holidaychecker($iterator);

        $start = new \DateTimeImmutable('2016-01-01');
        $end   = new \DateTimeImmutable('2016-12-31');
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $date) {
            $result = $checker->check($date);
        }

        $this->assertTrue(true);
    }
}

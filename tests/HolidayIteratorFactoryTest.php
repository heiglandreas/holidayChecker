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
 * @since     10.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\HolidayIterator;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use Org_Heigl\Holidaychecker\IteratorItem\Date;
use Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp;
use Org_Heigl\Holidaychecker\IteratorItem\Easter;
use Org_Heigl\Holidaychecker\IteratorItem\Relative;
use PHPUnit\Framework\TestCase;

class HolidayIteratorFactoryTest extends TestCase
{
    /**
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getElement
     * @covers \Org_Heigl\Holidaychecker\HolidayIterator::append
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::createIteratorFromXmlFile
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getFree
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::__construct
     */
    public function testThatFactoryReturnsCorrectStuffFromFile()
    {
        $file = __DIR__ . '/_assets/test.xml';
        $factory = new HolidayIteratorFactory();
        $result = $factory->createIteratorFromXmlFile($file);

        $this->assertInstanceof(HolidayIterator::class, $result);
        $this->assertInstanceof(Date::class, $result[0]);
        $this->assertInstanceof(DateFollowUp::class, $result[1]);
        $this->assertInstanceof(Relative::class, $result[2]);
        $this->assertInstanceof(Easter::class, $result[3]);
    }

    /**
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getElement
     * @covers \Org_Heigl\Holidaychecker\HolidayIterator::append
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::createIteratorFromISO3166
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::createIteratorFromXmlFile
     * @covers \Org_Heigl\Holidaychecker\HolidayIteratorFactory::getFree
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Date::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Relative::__construct
     */
    public function testThatFactoryReturnsCorrectStuffFromISOCode()
    {
        $factory = new HolidayIteratorFactory();
        $result = $factory->createIteratorFromISO3166('DE');

        $this->assertInstanceof(HolidayIterator::class, $result);
    }
}

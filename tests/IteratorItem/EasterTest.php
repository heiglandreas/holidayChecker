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

use Org_Heigl\Holidaychecker\IteratorItem\Easter;
use PHPUnit\Framework\TestCase;

class EasterTest extends TestCase
{
    /**
     * @dataProvider easterProvider
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getName
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::isHoliday
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::__construct
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::dateMatches
     * @covers \Org_Heigl\Holidaychecker\IteratorItem\Easter::getEaster
     */
    public function testThatEasterIsIdentifiedCorrectly($dateTime, $offset, $result, $name, $isHoliday)
    {
        $easter = new Easter($name, $isHoliday, $offset);
        $this->assertEquals($result, $easter->dateMatches($dateTime));
        $this->assertEquals($name, $easter->getName());
        $this->assertEquals($isHoliday, $easter->isHoliday());
    }

    public function easterProvider()
    {
        return [
            [new \DateTime('2017-04-06 12:00:00+00:00'), -10, true, 'test', true],
            [new \DateTime('2017-04-16 12:00:00+00:00'), 0, true, 'test', true],
            [new \DateTime('2017-04-17 12:00:00+00:00'), 0, false, 'test', false],
            [new \DateTime('2017-04-17 12:00:00+00:00'), 1, true, 'test', true],
        ];
    }
}

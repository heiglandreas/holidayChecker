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

use DateTime;
use Org_Heigl\Holidaychecker\IteratorItem\Relative;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Relative::class)]
class RelativeTest extends TestCase
{
	/** @dataProvider dateProvider */
	#[DataProvider('dateProvider')]
    public function testThatDateTestWorks(
		DateTime $dateTime,
		int $day,
		int $month,
		string $relation,
		bool $result,
		string $name,
		bool $isHoliday
	): void {
        $easter = new Relative($name, $isHoliday, $day, $month, $relation);
        $this->assertEquals($result, $easter->dateMatches($dateTime));
        $this->assertEquals($name, $easter->getName());
        $this->assertEquals($isHoliday, $easter->isHoliday());
    }

	/**
     * @return array{
     *     DateTime,
     *     int,
     *     int,
     *     string,
     *     bool,
     *     string,
     *     bool
     * }[]
     */
    public static function dateProvider()
    {
        return [
            [new DateTime('2017-12-03 12:00:00+00:00'), 25, 12, 'last sunday -3 weeks', true, 'test', true],
            [new DateTime('2017-12-17 12:00:00+00:00'), 24, 12, 'last sunday', true, 'test', true],
            [new DateTime('2017-12-18 12:00:00+00:00'), 24, 12, 'last sunday', false, 'test', true],
            [new DateTime('2017-06-19 12:00:00+00:00'), 17, 04, 'next wednesday +2 months', true, 'test', false],
            [new DateTime('2017-06-18 12:00:00+00:00'), 17, 04, 'next wednesday +2 months', false, 'test', false],
        ];
    }
}

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
use Org_Heigl\Holidaychecker\IteratorItem\EasterOrthodox;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(EasterOrthodox::class)]
class EasterOrthodoxTest extends TestCase
{
	/** @dataProvider easterProvider */
	#[DataProvider('easterProvider')]
    public function testThatEasterIsIdentifiedCorrectly(
		DateTime $dateTime,
		int $offset,
		bool $result,
		string $name,
		bool $isHoliday
	): void {
        $easter = new EasterOrthodox($name, $isHoliday, $offset);
        $this->assertEquals($result, $easter->dateMatches($dateTime));
        $this->assertEquals($name, $easter->getName());
        $this->assertEquals($isHoliday, $easter->isHoliday());
    }

	/**
	 * @return array{
	 *     DateTime,
	 *     int,
	 *     bool,
	 *     string,
	 *     bool
	 * }[]
	 */
	public static function easterProvider()
    {
        return [
            [new DateTime('2016-04-20 12:00:00+00:00'), -10, true, 'test', true],
            [new DateTime('2016-05-01 12:00:00+00:00'), 0, true, 'test', true],
            [new DateTime('2017-04-16 12:00:00+00:00'), 0, true, 'test', false],
            [new DateTime('2018-04-08 12:00:00+00:00'), 0, true, 'test', true],
        ];
    }
}

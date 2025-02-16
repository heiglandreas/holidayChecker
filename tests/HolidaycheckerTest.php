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
 * @since     09.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use Org_Heigl\Holidaychecker\Holiday;
use Org_Heigl\Holidaychecker\Holidaychecker;
use Org_Heigl\Holidaychecker\HolidayIterator;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use Org_Heigl\Holidaychecker\IteratorItem\Date;
use Org_Heigl\Holidaychecker\IteratorItem\Easter;
use Org_Heigl\Holidaychecker\IteratorItem\Relative;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function date;
use function microtime;
use function sprintf;

#[CoversClass(HolidayIteratorFactory::class)]
#[CoversClass(Holidaychecker::class)]
#[CoversClass(Holiday::class)]
#[CoversClass(HolidayIterator::class)]
#[CoversClass(Date::class)]
#[CoversClass(Easter::class)]
#[CoversClass(Relative::class)]
class HolidaycheckerTest extends TestCase
{
	/** @dataProvider integrationProvider */
	#[DataProvider('integrationProvider')]
    public function testIntegration(
		DateTime $date,
		bool $holiday,
		bool $named,
		string $name
	): void {
        $factory = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromXmlFile(__DIR__ . '/../share/DE-HE.xml');
        $checker = new Holidaychecker($iterator);

        $result = $checker->check($date);
        $this->assertEquals($holiday, $result->isHoliday());
        $this->assertEquals($named, $result->isNamed());
        $this->assertEquals($name, $result->getName());
    }

	/**
     * @return array{
     *     DateTime,
     *     bool,
     *     bool,
     *     string
     * }[]
     */
    public static function integrationProvider()
    {
        return [
            [new DateTime('2017-04-16'), false, true, 'Ostersonntag'],
            [new DateTime('2017-04-17'), true, true, 'Ostermontag'],
        ];
    }

    public function testSpeed(): void
    {
        $factory = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromXmlFile(__DIR__ . '/../share/DE-HE.xml');
        $checker = new Holidaychecker($iterator);

        $currentYear = date('Y');
        $start = new DateTimeImmutable($currentYear . '-01-01');
        $end   = new DateTimeImmutable($currentYear . '-12-31');
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);

        $time = microtime(true);
        foreach ($period as $date) {
            $checker->check($date);
        }
        $duration = microtime(true) - $time;

        if ($duration >= 0.2) {
            $this->markTestSkipped(sprintf(
                'Parsing the complete year of %1$s took %2$0.5f seconds',
                $currentYear,
                $duration
            ));
        }
        $this->assertTrue($duration < 0.2);
    }

	/** @dataProvider datesProvider */
	#[DataProvider('datesProvider')]
    public function testDates(string $code, DateTimeImmutable $date, string $dayname): void
    {
        $factory = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromXmlFile(__DIR__ . '/../share/' . $code . '.xml');
        $checker = new Holidaychecker($iterator);

        $result = $checker->check($date);
        $this->assertEquals($dayname, $result->getName());
    }

	/**
	 * @return array{
	 *     string,
	 *     DateTimeImmutable,
	 *     string
	 * }[]
	 */
    public static function datesProvider()
    {
        return [
            ['AF', new DateTimeImmutable('2022-10-08'), 'Mawlid'],
        ];
    }
}

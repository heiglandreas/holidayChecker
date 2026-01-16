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
 * @since     21.02.2018
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\HolidaycheckerTest;

use DateTimeImmutable;
use DateTimeInterface;
use IntlCalendar;
use Org_Heigl\Holidaychecker\Calendar;
use Org_Heigl\Holidaychecker\CalendarDay;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CalendarDayTest extends TestCase
{
	public function testConstructorCalendar(): void
    {
        $calendar = IntlCalendar::createInstance();

        $class = new CalendarDay(2, 3, $calendar);

        self::assertInstanceOf(CalendarDay::class, $class);
		self::assertSame(2, $calendar->get(IntlCalendar::FIELD_DAY_OF_MONTH));
		self::assertSame(2, $calendar->get(IntlCalendar::FIELD_MONTH));
		self::assertSame(12, $calendar->get(IntlCalendar::FIELD_HOUR_OF_DAY));
		self::assertSame(0, $calendar->get(IntlCalendar::FIELD_MINUTE));
		self::assertSame(0, $calendar->get(IntlCalendar::FIELD_SECOND));
		self::assertSame(0, $calendar->get(IntlCalendar::FIELD_MILLISECOND));
	}

	/** @dataProvider theSameDayWithoutCheckingYearProvider */
	#[DataProvider('theSameDayWithoutCheckingYearProvider')]
    public function testIsSameDay(
		int $day,
		int $month,
		?int $year,
		string $calendar,
		DateTimeImmutable $compare,
		bool $result
	): void {
        $cal = IntlCalendar::createInstance(null, '@calendar=' . $calendar);

        $class = new CalendarDay($day, $month, $cal);
        if (null !== $year) {
            $class->setYear($year);
        }

        $this->assertSame($result, $class->isSameDay($compare));
    }

	/**
	 * @return array{
	 *     int,
	 *     int,
	 *     int|null,
	 *     string,
	 *     DateTimeImmutable,
	 *     bool
	 * }[]
	 */
    public static function theSameDayWithoutCheckingYearProvider(): array
	{
        return [
            'Month doesnt match' => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('1.2.2000'), false],
            'Day doesnt match'   => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('2.1.2000'), false],
            'Matches'            => [1, 1, null, Calendar::GREGORIAN, new DateTimeImmutable('1.1.2000'), true],
            'Matches year'       => [1, 1, 2017, Calendar::GREGORIAN, new DateTimeImmutable('1.1.2000'), false],
            'Rosh Hashana 5779'  => [1, 1, null, Calendar::HEBREW, new DateTimeImmutable('10.9.2018'), true],
            'Rosh Hashana 5778'  => [1, 1, null, Calendar::HEBREW, new DateTimeImmutable('21.9.2017'), true],
            // This should be the 15th of june according to https://en.wikipedia.org/wiki/Eid_al-Fitr
            'Eid al-Fitr 1439'   => [1, 10, null, Calendar::ISLAMIC, new DateTimeImmutable('14.6.2018'), true],
        ];
    }

	/** @dataProvider provideTheWeekdayIsReturnedCorrectly */
	#[DataProvider('provideTheWeekdayIsReturnedCorrectly')]
    public function testThatTheWeekdayIsReturnedCorrectly(
		int $day,
		int $month,
		int $year,
		string $calendar,
		int $result
	): void {
        $cal = IntlCalendar::createInstance(null, '@calendar=' . $calendar);
        $class = new CalendarDay($day, $month, $cal);

        $this->assertSame($result, $class->getWeekdayForGregorianYear($year));
    }

	/**
	 * @return array<string, array{
	 *     int,
	 *     int,
	 *     int,
	 *     string,
	 *     int
	 * }>
	 */
	public static function provideTheWeekdayIsReturnedCorrectly()
    {
        return [
            '1.January.2018 is Monday'   =>
                [1, 1, 2018, Calendar::GREGORIAN, IntlCalendar::DOW_MONDAY],
            '1.Tishrei.5780 is Monday'   =>
                [1, 1, 2019, Calendar::HEBREW, IntlCalendar::DOW_MONDAY],
            '1.Tishrei.5779 is Monday'   =>
                [1, 1, 2018, Calendar::HEBREW, IntlCalendar::DOW_MONDAY],
            '1.Tishrei.5778 is Thursday' =>
                [1, 1, 2017, Calendar::HEBREW, IntlCalendar::DOW_THURSDAY],
            '1.Tishrei.5777 is Monday'   =>
                [1, 1, 2016, Calendar::HEBREW, IntlCalendar::DOW_MONDAY],
            '1.Adar.5780 is Wednesday'   =>
                [1, 7, 2020, Calendar::HEBREW, IntlCalendar::DOW_WEDNESDAY],
            '1.Adar II.5779 is Friday'   =>
                [1, 7, 2019, Calendar::HEBREW, IntlCalendar::DOW_FRIDAY],
            '1.Adar.5778 is Friday' =>
                [1, 7, 2018, Calendar::HEBREW, IntlCalendar::DOW_FRIDAY],
            '1.Adar.5777 is Monday' =>
                [1, 7, 2017, Calendar::HEBREW, IntlCalendar::DOW_MONDAY],
            '4.Jumādá al-ākhirah 1440 should be Saturday'  =>
                [4, 6, 2019, Calendar::ISLAMIC_UMALQURA, IntlCalendar::DOW_SATURDAY],
            '4.Jumādá al-ākhirah 1439 should be Tuesday'  =>
                [4, 6, 2018, Calendar::ISLAMIC_UMALQURA, IntlCalendar::DOW_TUESDAY],
            '4.Jumādá al-ākhirah 1438 should be Friday'  =>
                [4, 6, 2017, Calendar::ISLAMIC_UMALQURA, IntlCalendar::DOW_FRIDAY],
            '4.Jumādá al-ākhirah 1437 should be Sunday'  =>
                [4, 6, 2016, Calendar::ISLAMIC_UMALQURA, IntlCalendar::DOW_SUNDAY],
        ];
    }

	/** @dataProvider followUpIsCalculatedCorrectlyProvider */
    #[DataProvider('followUpIsCalculatedCorrectlyProvider')]
    public function testThatFollowUpIsCalculatedCorrectly(
        int $day,
        int $month,
        DateTimeInterface $dateTime,
        string $followUp
    ): void {
        $cal = IntlCalendar::createInstance(null, '@calendar=' . Calendar::GREGORIAN);

        $class = new CalendarDay($day, $month, $cal);
        $class->setYear(2020);

        self::assertTrue($class->isFollowUpDay($dateTime, $followUp));
    }

	/**
	 * @return array{
	 *     int,
	 *     int,
	 *     DateTimeImmutable,
	 *     string
	 * }[]
	 */

	public static function followUpIsCalculatedCorrectlyProvider(): array
    {
        return [
            [25, 11, new DateTimeImmutable('2020-11-29T12:00:00Z'), 'sunday'],
            [30, 11, new DateTimeImmutable('2020-12-06T12:00:00Z'), 'sunday'],
//            [28, 12, new DateTimeImmutable('2021-01-03T12:00:00Z'), 'sunday'],
        ];
    }
}

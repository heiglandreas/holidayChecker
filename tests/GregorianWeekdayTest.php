<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use DateTimeImmutable;
use IntlCalendar;
use Org_Heigl\Holidaychecker\GregorianWeekday;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UnexpectedValueException;

class GregorianWeekdayTest extends TestCase
{
	/** @dataProvider workdayProvider */
	#[DataProvider('workdayProvider')]
	public function testFromStringWorksAsExpectedForKnownWeekdays(string $string): void
	{
		$expected = GregorianWeekday::fromString($string);
		self::assertInstanceOf(GregorianWeekday::class, $expected);
		self::assertSame($string, $expected->getValue());
		self::assertSame($expected, GregorianWeekday::fromString($string));
	}

	/**
	 * @return array{string}[]
	 */
	public static function workdayProvider(): array
	{
		return [
			['monday'],
			['tuesday'],
			['wednesday'],
			['thursday'],
			['friday'],
			['saturday'],
			['sunday'],
		];
	}

	public function testFromStringThrowsWithUnknownString(): void
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Weekday "wtf" is not known');
		GregorianWeekday::fromString('wtf');
	}

	public function testCreationFromKnownInteger(): void
	{
		self::assertSame(GregorianWeekday::tuesday(), GregorianWeekday::fromIntlWeekday(IntlCalendar::DOW_TUESDAY));
	}

	public function testCreationFromDateTimeInterface(): void
	{
		self::assertSame(GregorianWeekday::friday(), GregorianWeekday::fromDateTimeInterface(new DateTimeImmutable('2022-10-21 12:00:00+00:00')));
	}

	public function testCreationFromUnknownInteger(): void
	{
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('IntlCalendar weekday 15 could not be resolved');
		GregorianWeekday::fromIntlWeekday(15);
	}
}

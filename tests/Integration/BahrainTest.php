<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Org_Heigl\Holidaychecker\Holidaychecker;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BahrainTest extends TestCase
{
	/** @dataProvider dayProvider */
	#[DataProvider('dayProvider')]
	public function testBahrainHolidays(DateTimeImmutable $day): void
	{
		$factory = new HolidayIteratorFactory();
		$iterator = $factory->createIteratorFromISO3166('BH');
		$checker = new Holidaychecker($iterator);

		self::assertTrue($checker->check($day)->isHoliday());
	}

	/**
	 * @return array{DateTimeImmutable}[]
	 */
	public static function dayProvider(): array
	{
		return [
			'New Year' => [new DateTimeImmutable('2015-01-01 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-01-03 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-12-23 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-05-01 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-07-17 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-07-18 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-07-19 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-09-22 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-09-23 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-09-24 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-09-25 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-10-14 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-10-22 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-10-23 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-12-16 12:00:00', new DateTimeZone('Asia/Bahrain'))],
			[new DateTimeImmutable('2015-12-17 12:00:00', new DateTimeZone('Asia/Bahrain'))],
		];
	}
}

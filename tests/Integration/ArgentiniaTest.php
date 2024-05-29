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
use PHPUnit\Framework\TestCase;

class ArgentiniaTest extends TestCase
{
    public function testMainArgeninianHolidays()
    {
        $factory  = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromIso3166('AR');
        $checker  = new Holidaychecker($iterator);

        self::assertTrue($checker->check(new DateTimeImmutable('2022-01-01 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-02-28 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-03-01 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-03-24 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-02 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-15 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-05-01 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-05-23 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-06-20 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-06-20 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-07-09 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-08-15 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-10-10 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-11-21 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-12-08 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-12-25 12:00:00'))->isHoliday());
    }

    public function testArmenianArgeninianHolidays()
    {
        $factory  = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromIso3166('AR-armenian');
        $checker  = new Holidaychecker($iterator);

        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-24 12:00:00'))->isHoliday());
    }

    public function testCatholicArgeninianHolidays()
    {
        $factory  = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromIso3166('AR-catholic');
        $checker  = new Holidaychecker($iterator);

        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-13 12:00:00'))->isHoliday());
    }

    public function testJewishArgeninianHolidays()
    {
        $factory  = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromIso3166('AR-judaism');
        $checker  = new Holidaychecker($iterator);

        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-16 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-17 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-22 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-04-23 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-09-26 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-09-27 12:00:00'))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-10-05 12:00:00'))->isHoliday());
    }

    public function testIslamicArgeninianHolidays()
    {
        $factory  = new HolidayIteratorFactory();
        $iterator = $factory->createIteratorFromIso3166('AR-islam');
        $checker  = new Holidaychecker($iterator);

        self::assertTrue($checker->check(new DateTimeImmutable('2022-05-02 12:00:00', new DateTimeZone('America/Buenos_Aires')))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-07-09 12:00:00', new DateTimeZone('America/Buenos_Aires')))->isHoliday());
        self::assertTrue($checker->check(new DateTimeImmutable('2022-07-30 12:00:00', new DateTimeZone('America/Buenos_Aires')))->isHoliday());
    }
}

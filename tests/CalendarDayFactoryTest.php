<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use DateTimeImmutable;
use Org_Heigl\Holidaychecker\Calendar;
use Org_Heigl\Holidaychecker\CalendarDayFactory;
use Org_Heigl\Holidaychecker\Exceptions\UnknownCalendar;
use PHPUnit\Framework\TestCase;

class CalendarDayFactoryTest extends TestCase
{
    public function testCreateCalendarDayThrowsExceptionWithInvalidCalendar(): void
    {
        self::expectException(UnknownCalendar::class);
        CalendarDayFactory::createCalendarDay(12, 12, 'Foo');
    }

    public function testCreateCalendarDayReturnsDay(): void
    {
        $day = CalendarDayFactory::createCalendarDay(1, 1, Calendar::CHINESE);
        $day->setGregorianYear(2021);

        self::assertTrue($day->isSameDay(new DateTimeImmutable('2021-02-12T12:00:00Z')));
    }
}

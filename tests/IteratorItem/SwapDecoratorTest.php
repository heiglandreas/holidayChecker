<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest\IteratorItem;

use DateTimeImmutable;
use IntlGregorianCalendar;
use Org_Heigl\Holidaychecker\CalendarDay;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\IteratorItem\SwapDecorator;
use PHPUnit\Framework\TestCase;

class SwapDecoratorTest extends TestCase
{
	public function testDateMatchesRedirectsToWrappedInstance(): void
	{
		$wrapped = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();
		$wrapped->expects($this->once())->method('dateMatches');

		$decorator = new SwapDecorator($wrapped, new CalendarDay(1, 1, new IntlGregorianCalendar()));

		$decorator->dateMatches(new DateTimeImmutable());
	}
}

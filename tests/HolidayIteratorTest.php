<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\HolidayIterator;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use PHPUnit\Framework\TestCase;

class HolidayIteratorTest extends TestCase
{
	public function testAddingDifferentElementsWorksAsExpected(): void
	{
		$rightItem = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();

		$iterator = new HolidayIterator();

		self::assertCount(0, $iterator);
		$iterator->append($rightItem);
		self::assertCount(1, $iterator);
		$iterator->append('foo');
		self::assertCount(1, $iterator);
	}
}

<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use Org_Heigl\Holidaychecker\SwapDirection;
use PHPUnit\Framework\TestCase;

class SwapDirectionTest extends TestCase
{
	/**
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::forward
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::__construct
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::rewind
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::getValue
	 * @return void
	 */
	public function testGettingValue(): void
	{
		self::assertSame('forward', SwapDirection::forward()->getValue());
		self::assertSame('rewind', SwapDirection::rewind()->getValue());
	}

	/**
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::forward
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::__construct
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::rewind
	 * @covers \Org_Heigl\Holidaychecker\SwapDirection::getDateTimeDirection
	 * @return void
	 */
	public function testGettingDateModifyValue(): void
	{
		self::assertSame('next', SwapDirection::forward()->getDateTimeDirection());
		self::assertSame('previous', SwapDirection::rewind()->getDateTimeDirection());
	}
}

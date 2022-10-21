<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker;

class SwapRule
{
	private $swapDirection;

	private $swapToDay;

	/** @var GregorianWeekday[] */
	private $swapWhenDay;

	/**
	 * @param GregorianWeekday[] $swapWhenGregorianDay
	 */
	public function __construct(SwapDirection $direction, GregorianWeekday $swapToGregorianDay, GregorianWeekday ...$swapWhenGregorianDay)
	{
		$this->swapDirection = $direction;
		$this->swapToDay = $swapToGregorianDay;
		$this->swapWhenDay = $swapWhenGregorianDay;
	}

	public function getDirection(): SwapDirection
	{
		return $this->swapDirection;
	}

	public function getSwapToDay(): GregorianWeekday
	{
		return $this->swapToDay;
	}

	public function getSwapWhenDays(): array
	{
		return $this->swapWhenDay;
	}
}

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
	/** @var SwapDirection */
	private $swapDirection;

	/** @var GregorianWeekday */
	private $swapToDay;

	/** @var GregorianWeekday[] */
	private $swapWhenDay;

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

	/**
	 * @return GregorianWeekday[]
	 */
	public function getSwapWhenDays(): array
	{
		return $this->swapWhenDay;
	}
}

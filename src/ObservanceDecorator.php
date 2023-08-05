<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\Holidaychecker;

use DateTimeInterface;

final class ObservanceDecorator implements HolidayIteratorItemInterface
{
	/** @var int|null */
	private $firstObservance;

	/** @var int|null */
	private $lastObservance;

	/** @var HolidayIteratorItemInterface */
	private $wrapped;

	public function __construct(HolidayIteratorItemInterface $wrapped, ?int $firstObservance, ?int $lastObservance)
	{
		$this->wrapped = $wrapped;
		$this->firstObservance = $firstObservance;
		$this->lastObservance = $lastObservance;
	}

	public function dateMatches(DateTimeInterface $date): bool
	{
		if (!$this->isWithinObservance((int) $date->format('Y'))) {
			return false;
		}

		return $this->wrapped->dateMatches($date);
	}

	private function isWithinObservance(int $gregorianYear): bool
	{
		if (null !== $this->firstObservance && $this->firstObservance > $gregorianYear) {
			return false;
		}

		return null === $this->lastObservance || $this->lastObservance >= $gregorianYear;
	}

	public function getName(): string
	{
		return $this->wrapped->getName();
	}

	public function isHoliday(): bool
	{
		return $this->wrapped->isHoliday();
	}
}

<?php

declare(strict_types=1);

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     08.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\Holidaychecker\IteratorItem;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Org_Heigl\DateIntervalComparator\DateIntervalComparator;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use function easter_days;

class Easter implements HolidayIteratorItemInterface
{
	/** @var int */
	private $offset;

	/** @var bool */
	private $holiday;

	/** @var string */
	private $name;

	public function __construct(string $name, bool $holiday, int $offset)
	{
		$this->offset = $offset;
		$this->holiday = $holiday;
		$this->name = $name;
	}

	public function dateMatches(DateTimeInterface $date): bool
	{
		$year = (int) $date->format('Y');

		$easter = $this->getEaster($year);
		$day = $this->getOffsetDay($easter, $this->offset);

		$comparator = new DateIntervalComparator();
		return 0 > $comparator->compare($day->diff($date), new DateInterval('P1D'));
	}

	protected function getEaster(int $year): DateTimeImmutable
	{
		$base = new DateTimeImmutable($year . "-03-21", new DateTimeZone('UTC'));
		$days = easter_days($year);

		return $base->add(new DateInterval("P{$days}D"));
	}

	/**
	 * @param DateTime|DateTimeImmutable $date
	 * @return DateTime|DateTimeImmutable
	 */
	private function getOffsetDay($date, int $offset)
	{
		if ($offset < 0) {
			return $date->sub(new DateInterval('P' . $offset * -1 . 'D'));
		}

		return $date->add(new DateInterval('P' . $offset . 'D'));
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function isHoliday(): bool
	{
		return $this->holiday;
	}
}

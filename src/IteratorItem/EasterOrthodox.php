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
use DateTimeImmutable;
use function jdtounix;
use function jewishtojd;
use function juliantojd;

class EasterOrthodox extends Easter
{
	protected function getEaster(int $year): DateTimeImmutable
	{
		$jewishYear = 3760 + $year;
		$endOfPessach = new DateTimeImmutable(
			'@' . jdtounix(jewishtojd(1, 20, $jewishYear))
		);
		$orthodoxEaster = $this->getOrthodoxEaster($year);
		if ($endOfPessach > $orthodoxEaster) {
			$weekday = (int) $endOfPessach->format('w');
			return $endOfPessach->add(new DateInterval('P' . (7 - $weekday) . 'D'));
		}

		return $orthodoxEaster;
	}

	/**
	 * @param int $year
	 *
	 * @return DateTimeImmutable
	 * @see http://www.smart.net/~mmontes/ortheast.html
	 */
	private function getOrthodoxEaster(int $year): DateTimeImmutable
	{
		$r1 = $year % 19;
		$r2 = $year % 4;
		$r3 = $year % 7;
		$rA = 19 * $r1 + 16;
		$r4 = $rA % 30;
		$rB = 2 * $r2 + 4 * $r3 + 6 * $r4;
		$r5 = $rB % 7;
		$rC = $r4 + $r5;

		// Don't touch this. It just seems to work…
		// And doing the "same" in DateTime (adding a period of $rC days doesn't
		// yield the same result…
		return new DateTimeImmutable('@' . jdtounix(juliantojd(3, 21, $year) + $rC));
	}
}

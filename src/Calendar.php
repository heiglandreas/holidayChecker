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
 * @since     22.02.2018
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\Holidaychecker;

use function in_array;

class Calendar
{
	public const BUDDHIST = 'buddhist';
	public const CHINESE = 'chinese';
	public const COPTIC = 'coptic';
	public const ETHIOPIAN = 'ethiopian';
	public const GREGORIAN = 'gregorian';
	public const HEBREW = 'hebrew';
	public const INDIAN = 'indian';
	public const ISLAMIC = 'islamic';
	public const ISLAMIC_CIVIL = 'islamic-civil';
	public const ISLAMIC_UMALQURA = 'islamic-umalqura';
	public const ISLAMIC_RGSA = 'islamic-rgsa';
	public const ISLAMIC_TBLA = 'islamic-tbla';
	public const JAPANESE = 'japanese';
	public const PERSIAN = 'persian';

	public static function isValidCalendarName(string $calendarname): bool
	{
		return in_array($calendarname, [
			self::BUDDHIST,
			self::CHINESE,
			self::COPTIC,
			self::ETHIOPIAN,
			self::GREGORIAN,
			self::HEBREW,
			self::INDIAN,
			self::ISLAMIC,
			self::ISLAMIC_CIVIL,
			self::ISLAMIC_RGSA,
			self::ISLAMIC_TBLA,
			self::ISLAMIC_UMALQURA,
			self::JAPANESE,
			self::PERSIAN,
		]);
	}
}

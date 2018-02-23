<?php
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

class Calendar
{
    const BUDDHIST  = 'buddhist';
    const CHINESE   = 'chinese';
    const COPTIC    = 'coptic';
    const ETHIOPIAN = 'ethiopian';
    const GREGORIAN = 'gregorian';
    const HEBREW    = 'hebrew';
    const INDIAN    = 'indian';
    const ISLAMIC   = 'islamic';
    const JAPANESE  = 'japanese';
    const PERSIAN   = 'persian';

    public static function isValidCalendarName(string $calendarname) : bool
    {
        switch ($calendarname) {
            case self::BUDDHIST:
            case self::CHINESE:
            case self::COPTIC:
            case self::ETHIOPIAN:
            case self::GREGORIAN:
            case self::HEBREW:
            case self::INDIAN:
            case self::ISLAMIC:
            case self::JAPANESE:
            case self::PERSIAN:
                return true;
            default:
        }

        return false;
    }
}

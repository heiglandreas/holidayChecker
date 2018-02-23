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
 * @since     09.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\Holidaychecker;

use Org_Heigl\Holidaychecker\IteratorItem\Date;
use Org_Heigl\Holidaychecker\IteratorItem\DateFollowUp;
use Org_Heigl\Holidaychecker\IteratorItem\Easter;
use Org_Heigl\Holidaychecker\IteratorItem\EasterOrthodox;
use Org_Heigl\Holidaychecker\IteratorItem\Relative;

class HolidayIteratorFactory
{
    /**
     * Create a HolidayIterator from an XML-File
     *
     * The provided XML-File has to validate against the holiday.xsd-file you
     * can find in this projects "share" folder.
     *
     * @param string $file
     *
     * @return \Org_Heigl\Holidaychecker\HolidayIterator
     */
    public function createIteratorFromXmlFile(string $file) : HolidayIterator
    {
        $iterator = new HolidayIterator();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->load($file);
        $dom->xinclude();

        if (! $dom->schemaValidate(__DIR__ . '/../share/holidays.xsd')) {
            throw new \Exception('XML-File does not validate agains schema');
        }
        foreach ($dom->documentElement->childNodes as $child) {
            if (! $child instanceof \DOMElement) {
                continue;
            }
            $iterator->append($this->getElement($child));
        }

        return $iterator;
    }

    /**
     * Create a HolidayIterator from an ISO 3166-code.
     *
     * @param string $isoCode
     *
     * @return \Org_Heigl\Holidaychecker\HolidayIterator
     */
    public function createIteratorFromISO3166(string $isoCode) : HolidayIterator
    {
        $file = __DIR__ . '/../share/%s.xml';
        $file1 = sprintf($file, $isoCode);

        if (! is_readable($file1)) {
            throw new \UnexpectedValueException(sprintf(
                'There is no holiday-file for %s',
                $isoCode
            ));
        }

        return self::createIteratorFromXmlFile($file1);
    }


    private function getElement(\DOMElement $child) : HolidayIteratorItemInterface
    {
        switch ($child->nodeName) {
            case 'easter':
                return new Easter(
                    $child->textContent,
                    $this->getFree($child),
                    $child->getAttribute('offset')
                );
            case 'easterorthodox':
                return new EasterOrthodox(
                    $child->textContent,
                    $this->getFree($child),
                    $child->getAttribute('offset')
                );
            case 'date':
                $day = CalendarDayFactory::createCalendarDay(
                    $child->getAttribute('day'),
                    $child->getAttribute('month'),
                    ($child->hasAttribute('calendar')?$child->getAttribute('calendar'): 'gregorian')
                );
                if ($child->hasAttribute('year')) {
                    $day->setYear($child->getAttribute('year'));
                }
                return new Date(
                    $child->textContent,
                    $this->getFree($child),
                    $day
                );
            case 'dateFollowUp':
                return new DateFollowUp(
                    $child->textContent,
                    $this->getFree($child),
                    $child->getAttribute('day'),
                    $child->getAttribute('month'),
                    $child->getAttribute('followup')
                );
            case 'relative':
                return new Relative(
                    $child->textContent,
                    $this->getFree($child),
                    $child->getAttribute('day'),
                    $child->getAttribute('month'),
                    $child->getAttribute('relation')
                );
        }
    }

    private function getFree(\DOMElement $element)
    {
        return ($element->getAttribute('free') === "true");
    }
}

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
 * @since     09.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.Holidaychecker
 */

namespace Org_Heigl\Holidaychecker;

use DOMDocument;
use DOMElement;
use Exception;
use Org_Heigl\Holidaychecker\Factory\DateFactory;
use Org_Heigl\Holidaychecker\Factory\DateFollowupFactory;
use Org_Heigl\Holidaychecker\Factory\DecorateFromDomElement;
use Org_Heigl\Holidaychecker\Factory\EasterFactory;
use Org_Heigl\Holidaychecker\Factory\EasterOrthodoxFactory;
use Org_Heigl\Holidaychecker\Factory\ItemFromDomElementCreator;
use Org_Heigl\Holidaychecker\Factory\ObservanceDecoratorFactory;
use Org_Heigl\Holidaychecker\Factory\RelativeFactory;
use Org_Heigl\Holidaychecker\Factory\SwapDecoratorFactory;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use function is_readable;
use function sprintf;

class HolidayIteratorFactory
{
	/** @var ItemFromDomElementCreator[] */
	private $factories;

	/** @var DecorateFromDomElement[] */
	private $decorators;

	public function __construct()
	{
		$this->factories = [
			new EasterFactory(),
			new EasterOrthodoxFactory(),
			new DateFactory(),
			new DateFollowupFactory(),
			new RelativeFactory(),
		];

		$this->decorators = [
			new ObservanceDecoratorFactory(),
			new SwapDecoratorFactory(),
		];
	}

	/**
	 * Create a HolidayIterator from an XML-File
	 *
	 * The provided XML-File has to validate against the holiday.xsd-file you
	 * can find in this projects "share" folder.
	 *
	 * @param string $file
	 *
	 * @return HolidayIterator
	 */
	public function createIteratorFromXmlFile(string $file): HolidayIterator
	{
		$iterator = new HolidayIterator();

		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->load($file);
		$dom->xinclude();

		if (!@$dom->schemaValidate(__DIR__ . '/../share/holidays.xsd')) {
			throw new Exception('XML-File does not validate agains schema');
		}
		foreach ($dom->documentElement->childNodes as $child) {
			if (!$child instanceof DOMElement) {
				continue;
			}
			if ($child->nodeName === 'resources') {
				continue;
			}

			try {
				$element = $this->getElement($child);
				$element = $this->decorateElement($element, $child);
				$iterator->append($element);
			} catch (Throwable $e) {
				// Do nothing on purpose
			}
		}

		return $iterator;
	}

	/**
	 * Create a HolidayIterator from an ISO 3166-code.
	 *
	 * @param string $isoCode
	 *
	 * @return HolidayIterator
	 */
	public function createIteratorFromISO3166(string $isoCode): HolidayIterator
	{
		$file = __DIR__ . '/../share/%s.xml';
		$file1 = sprintf($file, $isoCode);

		if (!is_readable($file1)) {
			throw new UnexpectedValueException(sprintf(
				'There is no holiday-file for %s',
				$isoCode
			));
		}

		return $this->createIteratorFromXmlFile($file1);
	}

	private function getElement(DOMElement $child): HolidayIteratorItemInterface
	{
		foreach ($this->factories as $factory) {
			$element = $factory->itemFromDomElement($child);
			if ($element instanceof HolidayIteratorItemInterface) {
				return $element;
			}
		}

		throw new RuntimeException('Unknown element encountered');
	}

	private function decorateElement(HolidayIteratorItemInterface $element, DOMElement $child): HolidayIteratorItemInterface
	{
		foreach ($this->decorators as $decorator) {
			$element = $decorator->decorate($element, $child);
		}

		return $element;
	}
}

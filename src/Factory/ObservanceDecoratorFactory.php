<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker\Factory;

use DOMElement;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\ObservanceDecorator;

class ObservanceDecoratorFactory implements DecorateFromDomElement
{
	public function decorate(HolidayIteratorItemInterface $element, DOMElement $domElement): HolidayIteratorItemInterface
	{
		if (! $domElement->hasAttribute('firstobservance') && ! $domElement->hasAttribute('lastobservance')) {
			return $element;
		}

		return new ObservanceDecorator(
			$element,
			$domElement->hasAttribute('firstobservance') ? (int) $domElement->getAttribute('firstobservance') : null,
			$domElement->hasAttribute('lastobservance') ? (int) $domElement->getAttribute('lastobservance') : null,
		);
	}
}

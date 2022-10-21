<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest\Factory;

use DOMDocument;
use Org_Heigl\Holidaychecker\Factory\SwapDecoratorFactory;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\IteratorItem\SwapDecorator;
use PHPUnit\Framework\TestCase;

class SwapDecoratorFactoryTest extends TestCase
{
	public function testCreatingAllTheRules(): void
	{
		$doc = new DOMDocument('1.0');
		$elem = $doc->createElement('date');
		$node = $doc->appendChild($elem);
		$node->setAttribute('forwardto', 'monday');
		$node->setAttribute('forwardwhen', 'saturday sunday');
		$node->setAttribute('alternateforwardto', 'monday');
		$node->setAttribute('alternateforwardwhen', 'saturday sunday');
		$node->setAttribute('rewindto', 'monday');
		$node->setAttribute('rewindwhen', 'saturday sunday');
		$node->setAttribute('alternaterewindto', 'monday');
		$node->setAttribute('alternaterewindwhen', 'saturday sunday');
		$node->setAttribute('year', '2022');

		$item = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();

		$factory = new SwapDecoratorFactory();
		$decorator = $factory->decorate($item, $node);

		self::assertInstanceOf(SwapDecorator::class, $decorator);
	}
}

<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licensed under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest\Factory;

use DOMDocument;
use DOMElement;
use Org_Heigl\Holidaychecker\Factory\RelativeFactory;
use Org_Heigl\Holidaychecker\IteratorItem\Relative;
use PHPUnit\Framework\TestCase;

class RelativeFactoryTest extends TestCase
{
	public function testCreatingRelativeItemFromWrongNodeName(): void
	{
		$node = new DOMElement('foo');

		$factory = new RelativeFactory();
		self::assertNull($factory->itemFromDomElement($node));
	}

	public function testCreatingRelativeItemFromRightNodeName(): void
	{
		$doc = new DOMDocument('1.0');
		$elem = $doc->createElement('relative');
		$node = $doc->appendChild($elem);
		$this->assertInstanceOf(DOMElement::class, $node);
		$node->setAttribute('free', 'true');
		$node->setAttribute('day', '1');
		$node->setAttribute('month', '12');
		$node->setAttribute('relation', 'next tuesday');

		$factory = new RelativeFactory();
		$item = $factory->itemFromDomElement($node);

		self::assertInstanceOf(Relative::class, $item);
	}
}

<?php

declare(strict_types=1);

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use DateTimeImmutable;
use Org_Heigl\Holidaychecker\HolidayIteratorItemInterface;
use Org_Heigl\Holidaychecker\ObservanceDecorator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ObservanceDecoratorTest extends TestCase
{
	/** @dataProvider provider */
	#[DataProvider('provider')]
    public function testDecoratorWorksAsExpected(?int $firstObservance, ?int $lastObservance, int $testYear, bool $expectedResult): void
    {
		$decorated = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();
		if ($expectedResult === false) {
			$decorated->expects($this->never())->method('dateMatches');
		} else {
			$decorated->expects($this->once())->method('dateMatches')->willReturn(true);
		}
        $decorator = new ObservanceDecorator($decorated, $firstObservance, $lastObservance);

        self::assertEquals($expectedResult, $decorator->dateMatches(new DateTimeImmutable($testYear . '-06-06 12:00:00')));
    }

    /**
     * @return array<array{(int|null), (int|null), int, bool}>
     */
    public static function provider(): array
    {
        return [
            [null, null, 2022, true],
            [2022, null, 2021, false],
            [2022, null, 2022, true],
            [2022, null, 2023, true],
            [null, 2022, 2021, true],
            [null, 2022, 2022, true],
            [null, 2022, 2023, false],
            [2022, 2022, 2021, false],
            [2022, 2022, 2022, true],
            [2022, 2022, 2023, false],
        ];
    }

	public function testThatGetNameIsPassedOnCorrectly(): void
	{
		$decorated = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();
		$decorated->expects($this->once())->method('getName')->willReturn('foobar');
		$decorator = new ObservanceDecorator($decorated, null, null);

		self::assertSame('foobar', $decorator->getName());
	}

	public function testThatisHolidayIsPassedOnCorrectly(): void
	{
		$decorated = $this->getMockBuilder(HolidayIteratorItemInterface::class)->getMock();
		$decorated->expects($this->once())->method('isHoliday')->willReturn(true);
		$decorator = new ObservanceDecorator($decorated, null, null);

		self::assertTrue($decorator->isHoliday());
	}
}

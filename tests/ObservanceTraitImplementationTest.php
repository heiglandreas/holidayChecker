<?php
/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace Org_Heigl\HolidaycheckerTest;

use PHPUnit\Framework\TestCase;

class ObservanceTraitImplementationTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testTraitWorksAsExpected(?int $firstObservance, ?int $lastObservance, int $testYear, bool $expectedResult): void
    {
        $observance = new ObservanceTraitImplementation();
        $observance->setObservances($firstObservance, $lastObservance);

        self::assertEquals($expectedResult, $observance->isWithinObservance($testYear));
    }

    /**
     * @return array<array{int|null, int|null, int, boolean}>
     */
    public function provider(): array
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
}

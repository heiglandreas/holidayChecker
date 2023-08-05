<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker;

final class SwapDirection
{
	private const FORWARD = 'forward';
	private const REWIND = 'rewind';
	/** @var array<string, self> */
	private static $instances = [];
	/** @var string */
	private $value;

	private function __construct(string $value)
	{
		$this->value = $value;
	}

	public static function forward(): self
	{
		if (!isset(self::$instances[self::FORWARD])) {
			self::$instances[self::FORWARD] = new self(self::FORWARD);
		}

		return self::$instances[self::FORWARD];
	}

	public static function rewind(): self
	{
		if (!isset(self::$instances[self::REWIND])) {
			self::$instances[self::REWIND] = new self(self::REWIND);
		}

		return self::$instances[self::REWIND];
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function getDateTimeDirection(): string
	{
		switch ($this->value) {
			case self::REWIND:
				return 'previous';
			case self::FORWARD:
				return 'next';
		}

		return '';
	}
}

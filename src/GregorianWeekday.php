<?php

/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

declare(strict_types=1);

namespace Org_Heigl\Holidaychecker;

use DateTimeInterface;
use IntlCalendar;
use RuntimeException;
use UnexpectedValueException;
use function method_exists;
use function sprintf;
use function strtolower;

final class GregorianWeekday
{
	/** @var string */
	private $value;

	/** @var array<string, GregorianWeekday> */
	private static $instances = [];

	private const MONDAY = 'monday';
	private const TUESDAY = 'tuesday';
	private const WEDNESDAY = 'wednesday';
	private const THURSDAY = 'thursday';
	private const FRIDAY = 'friday';
	private const SATURDAY = 'saturday';
	private const SUNDAY = 'sunday';

	private function __construct(string $value)
    {
		$this->value = $value;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public static function monday(): self
	{
		if (! isset(self::$instances[self::MONDAY])) {
			self::$instances[self::MONDAY] = new self(self::MONDAY);
		}

		return self::$instances[self::MONDAY];
	}

	public static function tuesday(): self
	{
		if (! isset(self::$instances[self::TUESDAY])) {
			self::$instances[self::TUESDAY] = new self(self::TUESDAY);
		}

		return self::$instances[self::TUESDAY];
	}

	public static function wednesday(): self
	{
		if (! isset(self::$instances[self::WEDNESDAY])) {
			self::$instances[self::WEDNESDAY] = new self(self::WEDNESDAY);
		}

		return self::$instances[self::WEDNESDAY];
	}

	public static function thursday(): self
	{
		if (! isset(self::$instances[self::THURSDAY])) {
			self::$instances[self::THURSDAY] = new self(self::THURSDAY);
		}

		return self::$instances[self::THURSDAY];
	}

	public static function friday(): self
	{
		if (! isset(self::$instances[self::FRIDAY])) {
			self::$instances[self::FRIDAY] = new self(self::FRIDAY);
		}

		return self::$instances[self::FRIDAY];
	}

	public static function saturday(): self
	{
		if (! isset(self::$instances[self::SATURDAY])) {
			self::$instances[self::SATURDAY] = new self(self::SATURDAY);
		}

		return self::$instances[self::SATURDAY];
	}

	public static function sunday(): self
	{
		if (! isset(self::$instances[self::SUNDAY])) {
			self::$instances[self::SUNDAY] = new self(self::SUNDAY);
		}

		return self::$instances[self::SUNDAY];
	}

	public static function fromString(string $weekday): self
	{
		if (! method_exists(self::class, strtolower($weekday))) {
			throw new RuntimeException(sprintf(
				'Weekday "%s" is not known',
				$weekday
			));
		}

		/** @var GregorianWeekday $gregorianWeekday */
		$gregorianWeekday = [self::class, strtolower($weekday)]();

		return $gregorianWeekday;
	}

	public static function fromDateTimeInterface(DateTimeInterface $date): self
	{
		return self::fromString($date->format('l'));
	}

	public static function fromIntlWeekday(int $weekday): self
	{
		$mapper = [
			IntlCalendar::DOW_SUNDAY    => 'sunday',
			IntlCalendar::DOW_MONDAY    => 'monday',
			IntlCalendar::DOW_TUESDAY   => 'tuesday',
			IntlCalendar::DOW_WEDNESDAY => 'wednesday',
			IntlCalendar::DOW_THURSDAY  => 'thursday',
			IntlCalendar::DOW_FRIDAY    => 'friday',
			IntlCalendar::DOW_SATURDAY  => 'saturday',
		];
		if (! isset($mapper[$weekday])) {
			throw new UnexpectedValueException(sprintf(
				'IntlCalendar weekday %s could not be resolved',
				$weekday
			));
		}

		return self::fromString($mapper[$weekday]);
	}

	public function __toString(): string
	{
		return $this->getValue();
	}
}

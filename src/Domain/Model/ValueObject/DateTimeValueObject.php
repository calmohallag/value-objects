<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class DateTimeValueObject extends \DateTimeImmutable implements ValueObject
{
    private const ALIAS = '';

    protected const TIME_ZONE = 'UTC';

    final private function __construct($time, $timezone)
    {
        parent::__construct($time, $timezone);
    }

    public static function from(string $str): static
    {
        return new static($str, new \DateTimeZone(self::TIME_ZONE));
    }

    public static function fromFormat(string $str, string $format): static
    {
        $dateTime = parent::createFromFormat($format, $str, new \DateTimeZone(self::TIME_ZONE));

        if (false === $dateTime) {
            throw new \InvalidArgumentException('Failure on create DateTimeInmutable');
        }

        return static::from($dateTime->format(\DATE_ATOM));
    }

    public static function fromNow(): static
    {
        return static::from('now');
    }

    public static function fromTimestamp(int $timestamp): static
    {
        return static::fromFormat(\strval($timestamp), 'U');
    }

    public static function timeZone(): string
    {
        return self::TIME_ZONE;
    }

    public static function alias(): string
    {
        if ('' === static::ALIAS) {
            throw new \InvalidArgumentException(\sprintf(
                'Undefined Value Object [%s] NAME const',
                static::class
            ));
        }

        return static::ALIAS;
    }

    public function value(): string
    {
        return $this->format(\DATE_ATOM);
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    final public function __toString(): string
    {
        return $this->value();
    }
}

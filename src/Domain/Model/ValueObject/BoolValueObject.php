<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class BoolValueObject implements ValueObject
{
    private const ALIAS = '';

    private bool $value;

    final private function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function from(bool $value): static
    {
        return new static($value);
    }

    public static function true(): static
    {
        return static::from(true);
    }

    public static function false(): static
    {
        return static::from(false);
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

    public static function allowedValues(): array
    {
        return [
            true,
            false,
        ];
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }

    final public function jsonSerialize(): bool
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return (string) (int) $this->value;
    }
}

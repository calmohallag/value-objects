<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class FloatValueObject implements ValueObject
{
    private const ALIAS = null;

    private float $value;

    final private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function from(float $value): static
    {
        return new static($value);
    }

    public static function alias(): string
    {
        if (null === static::ALIAS) {
            throw new \InvalidArgumentException(\sprintf(
                'Undefined Value Object [%s] NAME const',
                static::class
            ));
        }

        return static::ALIAS;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equalTo(FloatValueObject $other): bool
    {
        return \get_class($other) === static::class && $other->value === $this->value;
    }

    public function isBiggerThan(FloatValueObject $other): bool
    {
        return \get_class($other) === static::class && $this->value > $other->value;
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return (string) $this->value;
    }
}

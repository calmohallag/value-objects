<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class IntValueObject implements ValueObject
{
    private const ALIAS = '';

    private int $value;

    final private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function from(int $value): static
    {
        return new static($value);
    }

    public static function alias(): string
    {
        if ('' === static::ALIAS) {
            throw new \InvalidArgumentException(\sprintf(
                'Undefined value object [%s] NAME const',
                static::class
            ));
        }

        return static::ALIAS;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equalTo(IntValueObject $other): bool
    {
        return \get_class($other) === static::class && $this->value === $other->value;
    }

    public function isBiggerThan(IntValueObject $other): bool
    {
        return \get_class($other) === static::class && $this->value > $other->value;
    }

    final public function jsonSerialize(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

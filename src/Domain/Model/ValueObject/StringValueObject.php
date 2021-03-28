<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class StringValueObject implements ValueObject
{
    private const ALIAS = '';

    private string $value;

    final private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): static
    {
        return new static($value);
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
        return $this->value;
    }

    public function shortValue(): string
    {
        if (false === \str_contains($this->value, '.')) {
            return $this->value;
        }

        $fieldValue = \explode('.', $this->value);

        return \implode('.', \array_slice($fieldValue, \count($fieldValue) - 2));
    }

    public function equalTo(StringValueObject $other): bool
    {
        return \get_class($other) === static::class && $this->value === $other->value;
    }

    final public function jsonSerialize(): string
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return $this->value;
    }
}

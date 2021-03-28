<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

abstract class CollectionValueObject implements \Iterator, \Countable, ValueObject
{
    private const ALIAS = '';

    private array $items;

    final private function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function from(array $items): static
    {
        return new static($items);
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

    public function current()
    {
        $item = \current($this->items);
        return $item ? $item : null;
    }

    public function only()
    {
        if ($this->count() > 1) {
            throw new \LogicException('Not  item only in collection');
        }

        return $this->current();
    }

    public function next(): void
    {
        \next($this->items);
    }

    public function key(): int|string|null
    {
        return \key($this->items);
    }

    public function valid(): bool
    {
        return \array_key_exists($this->key(), $this->items);
    }

    public function rewind(): void
    {
        \reset($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function walk(callable $func): void
    {
        \array_walk($this->items, $func);
    }

    public function filter(callable $func): static
    {
        return static::from(\array_values(\array_filter($this->items, $func)));
    }

    public function map(callable $func): static
    {
        return static::from(\array_map($func, $this->items));
    }

    public function reduce(callable $func, $initial)
    {
        return \array_reduce($this->items, $func, $initial);
    }

    protected function sort(callable $func): static
    {
        $items = $this->items;
        \usort($items, $func);

        return static::from($items);
    }

    protected function mergeCollection(self $self): static
    {
        return static::from(\array_merge($this->items(), $self->items()));
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function equalTo(self $other): bool
    {
        return \get_class($other) === static::class && $this->items == $other->items;
    }

    final public function toArray(): array
    {
        $serializedObject = \json_encode($this);
        if (false === $serializedObject) {
            throw new \InvalidArgumentException('Failure on json serialization');
        }

        return \json_decode($serializedObject, true);
    }

    final public function items(): array
    {
        return $this->items;
    }

    final protected function inCollection(object $item): bool
    {
        $items = $this->filter(
            function ($current) use ($item) {
                return $current === $item;
            }
        );

        return $items->count() > 0;
    }

    final protected function assertInCollection(object $item): void
    {
        if (false === $this->inCollection($item)) {
            throw new \InvalidArgumentException(\sprintf(
                'Item %s is not in collection %s',
                \json_encode($item),
                \json_encode($this)
            ));
        }
    }

    protected function addItem($item): static
    {
        $items = $this->items;
        $items[] = $item;

        return static::from($items);
    }

    protected function removeItem($item): static
    {
        return $this->filter(static fn ($current) => $current !== $item);
    }

    protected function findOneFrom(string $field, $value, $nullableReturn = false)
    {
        $filteredCollection = $this->filter(
            static function ($item) use ($field, $value) {
                return $item->$field() == $value;
            }
        );

        $object = $filteredCollection->current();

        if (null === $object && false === $nullableReturn) {
            throw new \InvalidArgumentException(\sprintf(
                'Item not found in %s by %s [%s]',
                static::class,
                $field,
                \strval($value)
            ));
        }

        return $object;
    }

    final public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function __toString(): string
    {
        $string = [];
        foreach ($this as $item) {
            $string[] = (string) $item;
        }

        return \implode(', ', $string);
    }
}

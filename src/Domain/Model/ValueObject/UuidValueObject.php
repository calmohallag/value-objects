<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class UuidValueObject extends StringValueObject
{
    public static function from(string $value): static
    {
        return parent::from(RamseyUuid::fromString($value)->toString());
    }

    public static function create(): static
    {
        return parent::from(RamseyUuid::uuid4()->toString());
    }
}

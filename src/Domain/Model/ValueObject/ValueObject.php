<?php
declare(strict_types=1);

namespace Calmohallag\Domain\Model\ValueObject;

interface ValueObject extends \JsonSerializable, \Stringable
{
    public static function alias(): string;
}

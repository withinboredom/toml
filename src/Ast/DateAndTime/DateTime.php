<?php

namespace Withinboredom\Toml\Ast\DateAndTime;

use DateTimeImmutable;
use DateTimeInterface;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class DateTime implements Detectable, Node
{
    private function __construct(public DateTimeInterface $value)
    {
    }

    public static function parse(Code $code): DateTime|null
    {
        if (OffsetDateTime::is($code)) {
            return new self(OffsetDateTime::parse($code)->time);
        }

        if (PartialTime::is($code)) {
            $time = PartialTime::parse($code);
            return new self(new DateTimeImmutable((string)($time?->render())));
        }

        return null;
    }

    public static function is(Code $code): bool
    {
        return OffsetDateTime::is($code) || PartialTime::is($code);
    }
}

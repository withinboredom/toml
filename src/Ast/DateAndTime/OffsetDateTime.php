<?php

namespace Withinboredom\Toml\Ast\DateAndTime;

use DateTimeImmutable;
use DateTimeInterface;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class OffsetDateTime implements Detectable, Node
{
    private function __construct(public DateTimeInterface $time)
    {
    }

    public static function is(Code $code): bool
    {
        return FullDate::is($code);
    }

    public static function parse(Code $code): OffsetDateTime|null
    {
        $date = FullDate::parse($code);
        if ($date === null) return null;
        $renderedTime = '';
        $renderedOffset = '';
        $offset = null;
        if ($code->consumeRange(['T', 't', ' '])) {
            $code->expect(['T', 't', ' ']);
            $time = PartialTime::parse($code);
            if ($time === null) return null;
            if ($code->peek() === 'Z') {
                $code->expect(['Z']);
                $offset = [0, 0];
            } else {
                if ($code->peek() === '+' || is_numeric($code->peek())) {
                    // offset is positive
                    $code->consumeRange(['+']);
                    $offset[0] = $code->consume(2);
                    $code->expect([':']);
                    $offset[1] = $code->consume(2);
                } else {
                    $code->expect(['-']);
                    $offset[0] = $code->consume(2);
                    $code->expect([':']);
                    $offset[1] = $code->consume(2);
                }
            }
            $renderedTime = $time->render();
            if ($offset !== null) {
                $renderedOffset = $offset[0] > 0 ? '+' : '-';
                $renderedOffset .= "{$offset[0]}:{$offset[1]}";
            }
        }
        return new self(new DateTimeImmutable("{$date->year}-{$date->month}-{$date->day}T{$renderedTime}{$renderedOffset}"));
    }
}

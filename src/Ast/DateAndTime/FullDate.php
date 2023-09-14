<?php

namespace Withinboredom\Toml\Ast\DateAndTime;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class FullDate implements Detectable, Node
{
    private function __construct(public int $year, public int $month, public int $day)
    {
    }

    public static function is(Code $code): bool
    {
        $next = $code->peek(10, true);
        return preg_match('/\d{4}-\d{2}-\d{2}/', $next) === 1;
    }

    public static function parse(Code $code): FullDate|null
    {
        $year = $code->consume(4);
        $code->expect(['-']);
        $month = $code->consume(2);
        if ($month > 12) {
            $code->addError('Month must be between 1 and 12: ' . $month . ' given.');
            return null;
        }
        $code->expect(['-']);
        $day = $code->consume(2);

        return new self($year, $month, $day);
    }
}

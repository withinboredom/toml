<?php

namespace Withinboredom\Toml\Ast\DateAndTime;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class PartialTime implements Detectable, Node
{
    private function __construct(public int $hour, public int $minute, public int $second, public int $fraction)
    {
    }

    public static function parse(Code $code): PartialTime|null
    {
        $hour = $code->consume(2);
        $code->expect([':']);
        $minute = $code->consume(2);
        $second = 0;
        $fraction = 0;
        if ($code->peek() === ':') {
            $code->expect([':']);
            $second = $code->consume(2);
            if ($code->peek() === '.') {
                $code->expect(['.']);
                $fraction = $code->consume();
            }
        }

        return new self($hour, $minute, $second, $fraction);
    }

    public static function is(Code $code): bool
    {
        $next = $code->peek(5, true);
        return preg_match('/\d{2}:\d{2}/', $next) === 1;
    }

    public function render(): string
    {
        $renderedTime = "{$this->hour}:{$this->minute}";
        if ($this->second) {
            $renderedTime .= ":{$this->second}";
        }
        if ($this->fraction) {
            $renderedTime .= ".{$this->fraction}";
        }
        return $renderedTime;
    }
}

<?php

namespace Withinboredom\Toml\Ast\Integer;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Helpers\Range;

class Integer implements Detectable, Node
{
    private function __construct(public int $value)
    {
    }

    public static function is(Code $code): bool
    {
        return is_numeric($code->peek()) || in_array($code->peek(), ['+', '-']);
    }

    public static function parse(Code $code): self
    {
        $negative = false;
        if ($code->peek() === '-') {
            $negative = true;
            $code->consume();
        }
        $code->consumeRange(['+']);
        if ($code->consumeRange(['0x'])) {
            $number = $code->consumeRange([
                new Range(ord('0'), ord('9')),
                new Range(ord('a'), ord('f')),
                new Range(ord('A'), ord('F')),
                '_'
            ]);
        } elseif ($code->consumeRange(['0o'])) {
            $number = $code->consumeRange([
                new Range(ord('0'), ord('7')),
                '_'
            ]);
        } elseif ($code->consumeRange(['0b'])) {
            $number = $code->consumeRange([
                new Range(ord('0'), ord('1')),
                '_'
            ]);
        } else {
            $number = $code->consumeRange([
                new Range(ord('0'), ord('9')),
                '_'
            ]);
        }
        $number = str_replace('_', '', $number);
        if ($negative) {
            $number = '-' . $number;
        }
        return new self((int)$number);
    }
}

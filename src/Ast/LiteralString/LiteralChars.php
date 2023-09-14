<?php

namespace Withinboredom\Toml\Ast\LiteralString;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Helpers\Range;

class LiteralChars implements Node
{
    private function __construct(public string $string)
    {
    }

    public static function parse(Code $code): LiteralChars
    {
        return new self($code->consumeRange([
            0x09,
            new Range(0x20, 0x26),
            new Range(0x28, 0x7e),
            new Range(0x80, 0xd7ff),
            new Range(0xe000, 0x10FFFF),
        ]));
    }
}

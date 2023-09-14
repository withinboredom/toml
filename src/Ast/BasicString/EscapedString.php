<?php

namespace Withinboredom\Toml\Ast\BasicString;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Helpers\Pattern;
use Withinboredom\Toml\Helpers\Range;

class EscapedString implements Node {
    private function __construct(public string $string) {}

    public static function parse(Code $code): EscapedString
    {
        $string = $code->consumeRange([
            ...Code::WS_CHAR,
            0x21,
            new Range(0x23, 0x5b),
            new Range(0x5d, 0x7e),
            new Range(0x80, 0xd7ff),
            new Range(0xe000, 0x10FFFF),
            '\"',
            '\\\\',
            '\b',
            '\e',
            '\f',
            '\n',
            '\r',
            '\t',
            new Pattern(["\\", 'x', Code::HEX_DIGITS, Code::HEX_DIGITS]),
            new Pattern(["\\", 'u', Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS]),
            new Pattern(["\\", "U", Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS, Code::HEX_DIGITS]),
        ]);

        return new self($string);
    }
}

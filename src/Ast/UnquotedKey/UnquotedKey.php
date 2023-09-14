<?php

namespace Withinboredom\Toml\Ast\UnquotedKey;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Helpers\Range;

class UnquotedKey implements Detectable, Node
{
    private function __construct(public string $value)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peekRange(self::getPattern());
    }

    private static function getPattern(): array
    {
        static $pattern = null;
        return $pattern ??= [
            new Range(ord('A'), ord('Z')),
            new Range(ord('a'), ord('z')),
            new Range(ord('0'), ord('9')),
            ord('_'),
            ord('-'),
            0xb2,
            0xb3,
            0xb9,
            new Range(0xbc, 0xbe),
            new Range(0xc0, 0xd6),
            new Range(0xd8, 0xf6),
            new Range(0xf8, 0x37d),
            new Range(0x37f, 0x1fff),
            new Range(0x200c, 0x200d),
            new Range(0x203f, 0x2040),
            new Range(0x2070, 0x218f),
            new Range(0x2460, 0x24ff),
            new Range(0x2c00, 0x2fef),
            new Range(0x3001, 0xd7ff),
            new Range(0xf900, 0xfdcf),
            new Range(0xfdf0, 0xfffd),
            new Range(0x10000, 0xeffff),
        ];
    }

    public static function parse(Code $code): UnquotedKey|null
    {
        $key = $code->consumeRange(self::getPattern());
        if ($key === '') {
            $code->addError('Expected unquoted key');
            return null;
        }

        return new self($key);
    }
}

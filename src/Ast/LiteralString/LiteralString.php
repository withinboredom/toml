<?php

namespace Withinboredom\Toml\Ast\LiteralString;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class LiteralString implements Detectable, Node {
    private function __construct(public string $value)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek() === Code::APOSTROPHE[0];
    }

    public static function parse(Code $code): LiteralString
    {
        $code->expect(Code::APOSTROPHE);
        $string = LiteralChars::parse($code)->string;
        $code->expect(Code::APOSTROPHE);
        return new self($string);
    }
}

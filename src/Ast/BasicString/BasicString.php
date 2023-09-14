<?php

namespace Withinboredom\Toml\Ast\BasicString;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class BasicString implements Detectable, Node
{
    private function __construct(public string $value)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek() === Code::QUOTATION_MARK[0];
    }

    public static function parse(Code $code): BasicString
    {
        $code->expect(Code::QUOTATION_MARK);
        $value = EscapedString::parse($code);
        $code->expect(Code::QUOTATION_MARK);

        return new self($value->string);
    }
}

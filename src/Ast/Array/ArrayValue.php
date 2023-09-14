<?php

namespace Withinboredom\Toml\Ast\Array;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class ArrayValue implements Detectable, Node
{
    private function __construct(public array $values)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek() === '[';
    }

    public static function parse(Code $code): ArrayValue|null
    {
        $code->expect(['[']);
        $values = ArrayValues::parse($code)->values;
        WsCommentNl::parse($code);
        $code->expect([']']);

        return new self($values);
    }
}

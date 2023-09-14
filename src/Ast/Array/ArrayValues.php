<?php

namespace Withinboredom\Toml\Ast\Array;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\KeyValuePairs\Val;
use Withinboredom\Toml\Ast\Node;

class ArrayValues implements Node
{
    private function __construct(public array $values)
    {
    }

    public static function parse(Code $code): ArrayValues|null
    {
        $values = [];
        keepGoing:
        WsCommentNl::parse($code);
        $values[] = Val::parse($code)->value;
        WsCommentNl::parse($code);
        if ($code->peek() === ',') {
            $code->consume();
            goto keepGoing;
        }
        return new self($values);
    }
}

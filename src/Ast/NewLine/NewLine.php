<?php

namespace Withinboredom\Toml\Ast\NewLine;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class NewLine implements Detectable, Node
{
    public static function is(Code $code): bool
    {
        return $code->peek() === "\n" || $code->peek(2) === "\r\n";
    }

    public static function parse(Code $code): null
    {
        $code->expect(["\n", "\r\n"], silentFail: true);
        return null;
    }
}

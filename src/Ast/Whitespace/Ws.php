<?php

namespace Withinboredom\Toml\Ast\Whitespace;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class Ws implements Detectable, Node
{
    public static function is(Code $code): bool
    {
        return $code->peek() === ' ' || $code->peek() === "\t";
    }

    public static function parse(Code $code): null
    {
        $code->consumeRange([' ', "\t"]);
        return null;
    }
}

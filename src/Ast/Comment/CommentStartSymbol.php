<?php

namespace Withinboredom\Toml\Ast\Comment;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class CommentStartSymbol implements Detectable, Node
{
    public static function is(Code $code): bool
    {
        return $code->peek() === '#';
    }

    public static function parse(Code $code): null
    {
        $code->expect(['#']);
        return null;
    }
}

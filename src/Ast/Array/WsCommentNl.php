<?php

namespace Withinboredom\Toml\Ast\Array;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Comment\Comment;
use Withinboredom\Toml\Ast\NewLine\NewLine;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\Whitespace\Ws;

class WsCommentNl implements Node
{
    public static function parse(Code $code): null
    {
        Ws::parse($code);
        if (Comment::is($code)) {
            Comment::parse($code);
        }
        if (NewLine::is($code)) {
            NewLine::parse($code);
        }

        return null;
    }
}

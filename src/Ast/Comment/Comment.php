<?php

namespace Withinboredom\Toml\Ast\Comment;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class Comment implements Detectable, Node {
    private function __construct(public string $comment) {}

    public static function is(Code $code): bool
    {
        return CommentStartSymbol::is($code);
    }

    public static function parse(Code $code): Comment
    {
        CommentStartSymbol::parse($code);
        return new self(trim(AllowedCommentChars::parse($code)->comment));
    }
}

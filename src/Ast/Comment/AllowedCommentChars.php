<?php

namespace Withinboredom\Toml\Ast\Comment;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Helpers\Range;

class AllowedCommentChars implements Node
{
    private function __construct(public string $comment)
    {
    }

    public static function parse(Code $code): AllowedCommentChars
    {
        return new self($code->consumeRange([
            new Range(0x01, 0x09),
            new Range(0x0e, 0x7f),
            new Range(0x80, 0xd7ff),
            new Range(0xe000, 0xfffd)
        ]));
    }
}

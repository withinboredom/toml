<?php

namespace Withinboredom\Toml\Ast\MultiLineLiteralString;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\LiteralString\LiteralChars;
use Withinboredom\Toml\Ast\Node;

class LiteralString implements Detectable, Node
{

    private function __construct(public string $body)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek(3) === "'''";
    }

    public static function parse(Code $code): LiteralString|null
    {
        $code->expect(["'''"]);
        $code->consumeRange(["\n", "\r\n"]);
        $body = '';
        keepGoing:
        $body .= LiteralChars::parse($code)->string;
        $body .= $code->consumeRange(["\n", "\r\n"]);
        if ($code->peek(3) !== "'''") {
            $body .= $code->consumeRange(["''"]);
            goto keepGoing;
        }
        $code->expect(["'''"]);
        return new self($body);
    }
}

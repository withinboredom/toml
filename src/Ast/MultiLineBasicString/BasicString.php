<?php

namespace Withinboredom\Toml\Ast\MultiLineBasicString;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class BasicString implements Detectable, Node
{
    private function __construct(public string $body)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek(3) === '"""';
    }

    public static function parse(Code $code): BasicString|null
    {
        $code->expect(['"""']);
        $code->consumeRange(["\n", "\r\n"]);
        $body = BasicBody::parse($code);
        $code->expect(['"""']);

        if ($body === null) {
            return null;
        }

        return new self($body->value);
    }
}

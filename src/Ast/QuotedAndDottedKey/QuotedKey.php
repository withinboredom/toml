<?php

namespace Withinboredom\Toml\Ast\QuotedAndDottedKey;

use Withinboredom\Toml\Ast\BasicString\BasicString;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\LiteralString\LiteralString;
use Withinboredom\Toml\Ast\Node;

class QuotedKey implements Detectable, Node
{
    private function __construct(public string $value)
    {
    }

    public static function parse(Code $code): Node|null
    {
        if (BasicString::is($code)) {
            return new self(BasicString::parse($code)->value);
        }

        if (LiteralString::is($code)) {
            return new self(LiteralString::parse($code)->value);
        }

        $code->addError('Expected quoted key');

        return null;
    }

    public static function is(Code $code): bool
    {
        return BasicString::is($code) || LiteralString::is($code);
    }
}

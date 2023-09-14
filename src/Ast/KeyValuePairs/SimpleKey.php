<?php

namespace Withinboredom\Toml\Ast\KeyValuePairs;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\QuotedAndDottedKey\QuotedKey;
use Withinboredom\Toml\Ast\UnquotedKey\UnquotedKey;

class SimpleKey implements Detectable, Node
{
    private function __construct(public string $value)
    {
    }

    public static function parse(Code $code): SimpleKey|null
    {
        if (QuotedKey::is($code)) {
            return new self(QuotedKey::parse($code)->value);
        }

        if (UnquotedKey::is($code)) {
            return new self(UnquotedKey::parse($code)->value);
        }

        $code->addError('Expected simple key');

        return null;
    }

    public static function is(Code $code): bool
    {
        return QuotedKey::is($code) || UnquotedKey::is($code);
    }
}

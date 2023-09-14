<?php

namespace Withinboredom\Toml\Ast\KeyValuePairs;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\QuotedAndDottedKey\DottedKey;

class Key implements Detectable, Node
{
    private function __construct(public string $name, public array $subKeys)
    {
    }

    public static function is(Code $code): bool
    {
        return DottedKey::is($code);
    }

    public static function parse(Code $code): Key|null
    {
        $key = DottedKey::parse($code);
        if ($key === null) {
            return null;
        }
        return new self($key->value, $key->subKeys);
    }
}

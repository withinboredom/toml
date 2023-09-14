<?php

namespace Withinboredom\Toml\Ast\QuotedAndDottedKey;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\KeyValuePairs\SimpleKey;
use Withinboredom\Toml\Ast\Node;

class DottedKey implements Detectable, Node
{
    private function __construct(public string $value, public array $subKeys)
    {
    }

    public static function is(Code $code): bool
    {
        return SimpleKey::is($code);
    }

    public static function parse(Code $code): DottedKey|null
    {
        $value = SimpleKey::parse($code)->value;
        if ($value === null) {
            return null;
        }
        $subKeys = [];
        nextKey:
        if ($code->peek() === '.') {
            $code->expect(['.']);
            $subKeys[] = SimpleKey::parse($code)->value;
            goto nextKey;
        }

        return new self($value, $subKeys);
    }
}

<?php

namespace Withinboredom\Toml\Ast\KeyValuePairs;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\Whitespace\Ws;

class KeyVal implements Detectable , Node {
    private function __construct(public array $key, public string|bool|array|\DateTimeInterface|float|int $value)
    {
    }

    public static function is(Code $code): bool
    {
        return Key::is($code);
    }

    public static function parse(Code $code): KeyVal|null
    {
        $key = Key::parse($code);
        if($key === null) {
            return null;
        }
        $fullkey = [$key->name, ...$key->subKeys];
        Ws::parse($code);
        $code->expect(['=']);
        Ws::parse($code);
        $value = Val::parse($code)->value;

        return new self($fullkey, $value);
    }
}

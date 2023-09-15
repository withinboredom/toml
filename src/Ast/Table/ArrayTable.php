<?php

namespace Withinboredom\Toml\Ast\Table;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\KeyValuePairs\Key;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\Whitespace\Ws;

class ArrayTable implements Detectable, Node
{
    private function __construct(public array $key)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek(2, true) === '[[';
    }

    public static function parse(Code $code): Node|null
    {
        $code->expect(['[[']);
        Ws::parse($code);
        $key = Key::parse($code);
        Ws::parse($code);
        $code->expect([']]']);

        return new self([$key->name, ...$key->subKeys]);
    }
}

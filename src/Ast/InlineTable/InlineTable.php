<?php

namespace Withinboredom\Toml\Ast\InlineTable;

use Withinboredom\Toml\Ast\Array\WsCommentNl;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\KeyValuePairs\KeyVal;
use Withinboredom\Toml\Ast\Node;

class InlineTable implements Detectable, Node
{
    private function __construct(public array $values)
    {
    }

    public static function parse(Code $code): Node|null
    {
        $builder = [];
        $code->expect(['{']);
        keepGoing:
        WsCommentNl::parse($code);
        if (KeyVal::is($code)) {
            $val = KeyVal::parse($code);
            if ($val === null) {
                goto keepGoing;
            }
            $temp = &$builder;
            foreach ($val->key as $key) {
                $temp[$key] ??= [];
                $temp = &$temp[$key];
            }
            $temp = $val->value;
        }
        WsCommentNl::parse($code);
        if ($code->peek() === ',') {
            goto keepGoing;
        }
        $code->expect(['}']);

        return new self($builder);
    }

    public static function is(Code $code): bool
    {
        return $code->peek() === '{';
    }
}

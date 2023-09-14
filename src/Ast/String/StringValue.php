<?php

namespace Withinboredom\Toml\Ast\String;


use Withinboredom\Toml\Ast\BasicString\BasicString;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\LiteralString\LiteralString;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\MultiLineBasicString\BasicString as MultiLineString;
use Withinboredom\Toml\Ast\MultiLineLiteralString\LiteralString as MultiLineLiteralString;

class StringValue implements Detectable, Node
{
    private function __construct(public string $body)
    {
    }

    public static function parse(Code $code): StringValue|null
    {
        if (MultiLineString::is($code)) {
            return new self(MultiLineString::parse($code)->body);
        }

        if (BasicString::is($code)) {
            return new self(BasicString::parse($code)->value);
        }

        if (MultiLineLiteralString::is($code)) {
            return new self(MultiLineLiteralString::parse($code)->body);
        }

        if (LiteralString::is($code)) {
            return new self(LiteralString::parse($code)->value);
        }

        $code->addError('Expected string value');

        return null;
    }

    public static function is(Code $code): bool
    {
        return MultiLineString::is($code) || BasicString::is($code) || LiteralString::is($code) || MultiLineLiteralString::is($code);
    }
}

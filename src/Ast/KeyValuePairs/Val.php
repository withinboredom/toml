<?php

namespace Withinboredom\Toml\Ast\KeyValuePairs;


use Withinboredom\Toml\Ast\Array\ArrayValue;
use Withinboredom\Toml\Ast\Boolean\Boolean;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\String\StringValue;

class Val implements Node
{
    private function __construct(public string|bool|array|\DateTimeInterface|float|int $value)
    {
    }

    public static function parse(Code $code): Val|null
    {
        if (StringValue::is($code)) {
            return new self(StringValue::parse($code)->body);
        }

        if (Boolean::is($code)) {
            return new self(Boolean::parse($code)->value);
        }

        if (ArrayValue::is($code)) {
            return new self(ArrayValue::parse($code)->values);
        }

        $code->addError('Expected string, bool, array, table, datetime, or numeric value, got ' . $code->peek() . ' instead.');

        return null;
    }
}

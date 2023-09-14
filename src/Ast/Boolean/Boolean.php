<?php

namespace Withinboredom\Toml\Ast\Boolean;


use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Node;

class Boolean implements Detectable, Node
{
    private function __construct(public bool $value)
    {
    }

    public static function is(Code $code): bool
    {
        return $code->peek(4) === 'true' || $code->peek(5) === 'false';
    }

    public static function parse(Code $code): self|null
    {
        $value = $code->consumeRange(['true', 'false']);
        if (empty($value)) {
            $code->addError('Expected boolean value');
            return null;
        }
        return new self($value === 'true');
    }
}

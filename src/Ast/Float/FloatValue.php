<?php

namespace Withinboredom\Toml\Ast\Float;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Detectable;
use Withinboredom\Toml\Ast\Integer\Integer;
use Withinboredom\Toml\Ast\Node;

class FloatValue implements Detectable, Node
{
    private function __construct(public float|int $value)
    {
    }

    public static function is(Code $code): bool
    {
        return Integer::is($code);
    }

    public static function parse(Code $code): FloatValue|null
    {
        $intPart = Integer::parse($code);
        $expPart = null;
        $fracPart = null;
        if ($code->consumeRange(['e'])) {
            $expPart = Integer::parse($code);
        } elseif ($code->consumeRange(['.'])) {
            $fracPart = Integer::parse($code);
            if ($code->consumeRange(['e'])) {
                $expPart = Integer::parse($code);
            }
        }
        if ($expPart === null && $fracPart === null) {
            return new self($intPart->value);
        }

        // fracpart must be set
        if ($expPart === null) {
            return new self("{$intPart->value}.{$fracPart->value}");
        }

        // expPart must be set
        if ($fracPart === null) {
            return new self("{$intPart->value}e{$expPart->value}");
        }

        return new self("{$intPart->value}.{$fracPart->value}e{$expPart->value}");
    }
}

<?php

namespace Withinboredom\Toml\Ast;

interface Detectable
{
    public static function is(Code $code): bool;
}

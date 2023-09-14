<?php

namespace Withinboredom\Toml\Ast;

interface Node
{
    public static function parse(Code $code): Node|null;
}

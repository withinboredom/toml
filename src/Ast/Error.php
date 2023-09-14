<?php

namespace Withinboredom\Toml\Ast;

readonly class Error
{
    public function __construct(public int $line, public int $column, public string $message)
    {
    }
}

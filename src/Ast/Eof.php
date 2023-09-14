<?php

namespace Withinboredom\Toml\Ast;

class Eof extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Unexpected end of file');
    }
}

<?php

namespace Withinboredom\Toml\Ast\MultiLineBasicString;


use Withinboredom\Toml\Ast\BasicString\EscapedString;
use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Node;

class BasicBody implements Node {
    private function __construct(public string $value) {}

    public static function parse(Code $code): BasicBody|null
    {
        $body = '';
        keepGoing:
        $body .= EscapedString::parse($code)->string;
        if($code->peek(3) !== '"""' && $code->peek(2) === '""') {
            $body .= '""';
            $code->consume(2);
            goto keepGoing;
        }

        return new self($body);
    }
}

<?php

namespace Withinboredom\Toml;

use Closure;

class Parser
{
    const WS_CHAR = [" ", "\t"];
    const NEW_LINE = ["\n", "\r\n"];

    const KEY_CHARS = [
        0x2d,
        0x5f,
        0xb2,
        0xb3,
        0xb9,
        [0xbc, 0xbe],
        [0xc0, 0xd6],
        [0xd8, 0xf6],
        [0xf8, 0x37d],
        [0x37f, 0x1fff],
        [0x200c, 0x200d],
        [0x203f, 0x2040],
        [0x2070, 0x218f],
        [0x2460, 0x24ff],
        [0x2c00, 0x2fef],
        [0x3001, 0xd7ff],
        [0xf900, 0xfdcf],
        [0xfdf0, 0xfffd],
        [0x10000, 0xeffff],
    ];

    private string $input = <<<TEST
# This is a TOML document. Boom.
ints = [1, 2, 3, ]
floats = [1.1, 2.1, 3.1]
strings = ["a", "b", "c"]
dates = [
  1987-07-05T17:45:00Z,
  1979-05-27T07:32:00Z,
  2006-06-01T11:00:00Z,
]
comments = [
         1,
         2, #this is ok
]
TEST;

    private int $position = 0;

    private array $result = [];

    public function parse(): array
    {
        return $this->toml();
    }

    private function toml(): array
    {
        while ($this->expression()) {
            if (!$this->consumeNewLine()) {
                return $this->result;
            }
        }

        throw new \Exception('Unexpected end of input');
    }

    private function expression(): bool
    {
        $ws = $this->consumeWhitespace();
        $var = $this->keyval() || $this->table();
        $ws = $ws || $this->consumeWhitespace();
        if ($this->peek() === '#') {
            $this->consumeWhile(fn($char) => $char !== "\n");
            return true;
        }
        return $var || $ws;
    }

    private function consumeWhitespace(): bool
    {
        return strlen($this->consumeWhile(fn($char) => in_array($char, self::WS_CHAR, true))) !== 0;
    }

    private function consumeWhile(Closure $predicate): string
    {
        $result = '';
        $previous = '';
        while ($predicate($this->peek(), $previous)) {
            $result .= $this->consume();
        }
        return $result;
    }

    private function peek(int $count = 1): string
    {
        if ($this->position + $count > strlen($this->input)) {
            throw new \Exception('Unexpected end of input');
        }
        return substr($this->input, $this->position, $count);
    }

    private function consume(int $count = 1): string
    {
        $result = $this->peek($count);
        $this->position += $count;
        return $result;
    }

    private function keyval(): bool
    {
        return $this->key() || $this->keyvalSep() || $this->val();
    }

    private function key(): bool
    {
        return $this->simpleKey() || $this->dottedKey();
    }

    private function dottedKey(): false|string {

    }

    private function simpleKey(): false|string
    {
        return $this->quotedKey() || $this->unquotedKey();
    }

    private function quotedKey(): false|string
    {
        return $this->basicString() || $this->literalString();
    }

    private function basicString(): false|string
    {
        if ($this->peek() !== '"') {
            return false;
        }
        $this->consume();
        $value = $this->consumeWhile(fn($char, $previous) => $char !== '"' && $previous !== '\\');
        $this->consume();
        return $value;
    }

    private function literalString(): false|string
    {
        if ($this->peek() !== "'") {
            return false;
        }
        $this->consume();
        $value = $this->consumeWhile(fn($char, $previous) => $char !== "'");
        $this->consume();
        return $value;
    }

    private function unquotedKey(): false|string
    {
        $value = $this->consumeWhile(function ($x, $previous) {
            if (ctype_alnum($x))
                return true;

            foreach(self::KEY_CHARS as $char) {
                if (is_array($char)) {
                    if (ord($x) >= $char[0] && ord($x) <= $char[1]) {
                        return true;
                    }
                } else if (ord($x) === $char) {
                    return true;
                }
            }

            return false;
        });

        if(strlen($value) === 0) {
            return false;
        }

        return $value;
    }

    private function consumeNewLine(): bool
    {
        return strlen($this->consumeWhile(fn($char) => in_array($char, self::NEW_LINE, true))) !== 0;
    }
}

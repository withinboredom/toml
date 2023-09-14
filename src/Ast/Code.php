<?php

namespace Withinboredom\Toml\Ast;

use Exception;
use Withinboredom\Toml\Helpers\Pattern;
use Withinboredom\Toml\Helpers\Range;

class Code
{
    const WS_CHAR = [" ", "\t"];
    const NEW_LINE = ["\n", "\r\n"];
    const COMMENT_START_SYMBOL = ['#'];
    const QUOTATION_MARK = ['"'];
    const APOSTROPHE = ["'"];
    const MINUS = ['-'];
    const PLUS = ['+'];
    const UNDERSCORE = ['_'];
    const DIGIT_1_9 = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const DIGIT_0_7 = ['0', '1', '2', '3', '4', '5', '6', '7'];
    const DIGIT_0_1 = ['0', '1'];
    const HEX_PREFIX = ['0x'];
    const OCT_PREFIX = ['0o'];
    const BIN_PREFIX = ['0b'];
    const TRUE = ['true'];
    const FALSE = ['false'];
    const ARRAY_OPEN = ['['];
    const ARRAY_CLOSE = [']'];
    const ARRAY_SEP = [','];
    const INLINE_TABLE_OPEN = ['{'];
    const INLINE_TABLE_CLOSE = ['}'];
    const INLINE_TABLE_SEP = [','];
    const HEX_DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'A', 'B', 'C', 'D', 'E', 'F'];

    /**
     * @param string $source
     * @param int $cursor
     * @param array<Error> $errors
     */
    public function __construct(
        public readonly string $source,
        public int $cursor = 0,
        public array $errors = [],
        public int $line = 1,
        public int $column = 1,
    )
    {
    }

    public function expect(array $parts, bool $silentFail = false): void
    {
        $value = $this->consumeRange($parts, $silentFail);
        if ($value === '') {
            $this->errors[] = new Error($this->line, $this->column, 'Unexpected token: ' . $this->peek());
            if (count($this->errors) > 10) {
                throw new Exception('Too many errors');
            }
        }
    }

    /**
     * @param array<string|int|Range|Pattern> $ranges
     * @return string
     */
    public function consumeRange(array $ranges, bool $silentFail = false): string
    {
        $result = '';
        while (true) {
            if ($this->isEof()) {
                break;
            }
            foreach ($ranges as $range) {
                if (is_int($range)) {
                    $next = $this->peek(silentFail: $silentFail);
                    if (ord($next) === $range) {
                        $result .= $next;
                        $this->cursor++;
                        continue 2;
                    }
                } elseif (is_string($range)) {
                    $next = $this->peek(strlen($range), silentFail: $silentFail);
                    if ($next === $range) {
                        $result .= $next;
                        $this->cursor += strlen($range);
                        continue 2;
                    }
                } elseif ($range instanceof Range) {
                    $next = $this->peek(silentFail: $silentFail);
                    if ($range->includes(ord($next))) {
                        $result .= $next;
                        $this->cursor++;
                        continue 2;
                    }
                } elseif ($range instanceof Pattern) {
                    $next = $this->peek($range->length(), silentFail: $silentFail);
                    if ($range->includes($next)) {
                        $result .= $next;
                        $this->cursor += $range->length();
                        continue 2;
                    }
                }
            }
            break;
        }

        return $result;
    }

    public function isEof()
    {
        return $this->cursor >= strlen($this->source);
    }

    public function peek(int $amount = 1, bool $silentFail = false): string
    {
        if (!$silentFail && $this->cursor + $amount > strlen($this->source)) {
            throw new Eof();
        }
        if ($amount === 1) {
            return $this->source[$this->cursor] ?? '';
        }

        return substr($this->source, $this->cursor, $amount);
    }

    public function addError(string $message): void
    {
        $this->errors[] = new Error($this->line, $this->column, $message);
        $this->consumeToEolOrEof();
    }

    public function consumeToEolOrEof(): void
    {
        $this->cursor = strpos($this->source, "\n", $this->cursor);
    }

    public function consume(int $amount = 1): string
    {
        $result = $this->peek($amount);
        $this->advance($amount);
        return $result;
    }

    public function advance(int $amount = 1): void
    {
        $this->line += $lines = substr_count($this->source, "\n", $this->cursor, $amount);
        if ($lines > 0) {
            $this->column = $amount - strrpos($this->peek($amount), "\n", $this->cursor);
        } else {
            $this->column += $amount;
        }
        $this->cursor += $amount;
    }

    public function peekRange(array $ranges, int $amount = 1): bool
    {
        for ($i = 0; $i < $amount; $i++) {
            foreach ($ranges as $range) {
                if (is_int($range)) {
                    if (ord($this->source[$this->cursor + $i]) === $range) {
                        continue 2;
                    }
                } elseif (is_string($range)) {
                    if (substr($this->source, $this->cursor + $i, strlen($range)) === $range) {
                        continue 2;
                    }
                } elseif ($range instanceof Range) {
                    if ($range->includes(ord($this->source[$this->cursor + $i]))) {
                        continue 2;
                    }
                } elseif ($range instanceof Pattern) {
                    $next = substr($this->source, $this->cursor + $i, $range->length());
                    if ($range->includes($next)) {
                        continue 2;
                    }
                }
            }
            return false;
        }
        return true;
    }
}

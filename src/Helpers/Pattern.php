<?php

namespace Withinboredom\Toml\Helpers;

readonly class Pattern
{
    public function __construct(private array $pattern)
    {
    }

    public function includes(string $chars): bool
    {
        foreach ($this->pattern as $i => $pattern) {
            if (is_string($pattern)) {
                if ($pattern === $chars[$i]) {
                    continue;
                }
                return false;
            }

            if (is_array($pattern)) {
                if (in_array($chars[$i], $pattern, true)) {
                    continue;
                }
                return false;
            }

            if ($pattern instanceof Range) {
                if ($pattern->includes($chars[$i])) {
                    continue;
                }
                return false;
            }
        }

        return true;
    }

    public function length(): int
    {
        return count($this->pattern);
    }
}

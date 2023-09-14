<?php

namespace Withinboredom\Toml\Helpers;

readonly class Range {
    public function __construct(public int $start, public int $end)
    {
    }

    public function includes(int $position): bool {
        return $position >= $this->start && $position <= $this->end;
    }
}

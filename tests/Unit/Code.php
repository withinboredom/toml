<?php

use Withinboredom\Toml\Ast\BasicString\Code;
use Withinboredom\Toml\Helpers\Range;

it('should consume', function () {
    $code = new Code('foo');
    expect($code->consume())->toBe('f');
    expect($code->consume())->toBe('o');
    expect($code->consume())->toBe('o');
    expect(fn() => $code->consume())->toThrow(Exception::class);
});

it('should consume a range', function () {
    $code = new Code('{foo}');
    expect($code->consumeRange(['{']))->toBe('{')
        ->and($code->consumeRange([ord('f'), new Range(ord('a'), ord('z'))]))
        ->toBe('foo');
});

it('can properly expect', function () {
    $code = new Code('{foo}');
    $code->expect(['{']);
    expect(true)->toBeTrue()->and($code->errors)->toBeEmpty();
    $code->expect([' ']);
    expect($code->errors)->toHaveCount(1);
});

it('can track the current column and line', function () {
    $code = new Code(<<<TEST
abc
d
f
TEST
    );
    expect($code->consume())->toBe('a')
        ->and([$code->line, $code->column])->toBe([1, 2])
        ->and($code->consume(3))->toBe("bc\n")
        ->and([$code->line, $code->column])->toBe([2, 1])
        ->and($code->consume())->toBe('d')
        ->and([$code->line, $code->column])->toBe([2, 2]);
});

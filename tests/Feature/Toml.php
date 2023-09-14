<?php

it('can parse comments and lines', function () {
    $toml = <<<TOML
# This is a comment
     #this is another comment
     # and another
     
     # and another
TOML;

    $code = new \Withinboredom\Toml\Ast\BasicString\Code($toml);
    \Withinboredom\Toml\Ast\BasicString\Structure\Expression::parse($code);
    expect($code->errors)->toBeEmpty();
});

it('can parse strings', function() {
    $toml = <<<TOML
string.a = "a"
string.b = 'b'
string.c = """c"""
string.d = '''d'''
"hello" = true
foo = false
cancel = ["never", 'gonna', 
"""give""", '''you'''
# up
]
number.a = 1
number.b = 1.0
number.c = 1e1
number.d = 1.0e1
number.e = 1.0e+1
number.f = 1.0e-1
number.g = 1_000
TOML;
    $code = new \Withinboredom\Toml\Ast\Code($toml);
    $result = \Withinboredom\Toml\Ast\Structure\Expression::parse($code)->body;
    expect($result)->toBe([
        'string' => [
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
        ],
        'hello' => true,
        'foo' => false,
        'cancel' => [
            'never',
            'gonna',
            'give',
            'you',
        ],
        'number' => [
            'a' => 1,
            'b' => 1.0,
            'c' => 10.0,
            'd' => 10.0,
            'e' => 10.0,
            'f' => 0.1,
            'g' => 1000,
        ],
    ])
        ->and($code->errors)->toBeEmpty();
});

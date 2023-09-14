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
    ])
        ->and($code->errors)->toBeEmpty();
});

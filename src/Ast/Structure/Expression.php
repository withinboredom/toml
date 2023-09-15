<?php

namespace Withinboredom\Toml\Ast\Structure;

use Withinboredom\Toml\Ast\Code;
use Withinboredom\Toml\Ast\Comment\Comment;
use Withinboredom\Toml\Ast\Eof;
use Withinboredom\Toml\Ast\KeyValuePairs\KeyVal;
use Withinboredom\Toml\Ast\NewLine\NewLine;
use Withinboredom\Toml\Ast\Node;
use Withinboredom\Toml\Ast\Table\ArrayTable;
use Withinboredom\Toml\Ast\Table\StandardTable;
use Withinboredom\Toml\Ast\Whitespace\Ws;

class Expression implements Node
{

    private function __construct(public array $body)
    {
    }

    public static function parse(Code $code): Expression|null
    {
        $root = [];
        $built = &$root;
        actualExpression:
        Ws::parse($code);

        if (KeyVal::is($code)) {
            $next = KeyVal::parse($code);
            if ($next !== null) {
                $a = &$built;
                foreach ($next->key as $key) {
                    $a[$key] ??= [];
                    $a = &$a[$key];
                }
                $a = $next->value;
            }
        }

        if (ArrayTable::is($code)) {
            $next = ArrayTable::parse($code);
            if($next !== null) {
                $a = &$root;
                foreach($next->key as $key) {
                    $a[$key] ??= [];
                    $a = &$a[$key][]; // this is the only difference between this and standard table
                }
                $built = &$a;
            }
        } elseif (StandardTable::is($code)) {
            $next = StandardTable::parse($code);
            if ($next !== null) {
                $a = &$root;
                foreach ($next->key as $key) {
                    $a[$key] ??= [];
                    $a = &$a[$key];
                }
                $built = &$a;
            }
        }

        $isComment = false;
        try {
            $isComment = Comment::is($code);
        } catch (Eof) {
            // potentially the end of the file
        }

        if ($isComment) {
            try {
                Comment::parse($code);
            } catch (Eof) {
                // possible eof after comment
            }
        }

        $isNewLine = false;
        try {
            $isNewLine = NewLine::is($code);
        } catch (Eof) {
            // potentially the end of the file
        }

        if ($isNewLine) {
            NewLine::parse($code);
            goto actualExpression;
        }

        return new self($root);
    }
}

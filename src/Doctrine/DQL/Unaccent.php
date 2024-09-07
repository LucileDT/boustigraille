<?php

namespace App\Doctrine\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\TokenType;

/**
* Unaccent string using postgresql extension unaccent :
* http://www.postgresql.org/docs/current/static/unaccent.html
*
* Usage : StringFunction UNACCENT(string)
*
*/
class Unaccent extends FunctionNode
{
    private $string;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker): string
    {
        return 'UNACCENT(' . $this->string->dispatch($sqlWalker) . ')';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->string = $parser->StringPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}

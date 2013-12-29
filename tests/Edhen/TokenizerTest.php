<?php

namespace Edhen;

use Edhen\Token;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testTokensCanBeRead()
    {
        $tokenizer = new Tokenizer('{:foo 123}');

        $token = $tokenizer->nextToken();
        $this->assertEquals(Token::BRACE_OPEN, $token->getType());

        $token = $tokenizer->nextToken();
        $this->assertEquals(Token::KEYWORD, $token->getType());
        $this->assertEquals(':foo', $token->getValue());

        $token = $tokenizer->nextToken();
        $this->assertEquals(Token::NUMERIC, $token->getType());
        $this->assertEquals('123', $token->getValue());

        $token = $tokenizer->nextToken();
        $this->assertEquals(Token::BRACE_CLOSE, $token->getType());

        $this->assertNull($tokenizer->nextToken());
    }
}

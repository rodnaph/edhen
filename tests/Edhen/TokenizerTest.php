<?php

namespace Edhen;

use Edhen\Token;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    protected function assertTokens($edn, $tokens, $debug = false)
    {
        $tokenizer = new Tokenizer($edn);
        $tokenizer->setDebug($debug);

        foreach ($tokens as $expected) {
            $actual = $tokenizer->nextToken();

            $this->assertNotNull($actual);
            $this->assertEquals($expected->getType(), $actual->getType());
            $this->assertEquals($expected->getValue(), $actual->getValue());
        }

        $this->assertNull($tokenizer->nextToken());
    }

    public function testBooleansCanBeRead()
    {
        $this->assertTokens(
            'true false',
            array(
                new Token(Token::BOOLEAN_TRUE),
                new Token(Token::BOOLEAN_FALSE)
            )
        );
    }

    public function testNilsCanBeRead()
    {
        $this->assertTokens(
            'nil',
            array(
                new Token(Token::NIL)
            )
        );
    }

    public function testKeywordsCanBeRead()
    {
        $this->assertTokens(
            ':foo :foo/bar',
            array(
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::KEYWORD, ':foo/bar')
            )
        );
    }

    public function testSymbolsCanBeRead()
    {
        $this->assertTokens(
            'foo .*+!-_?$%&=#: foo.bar/baz',
            array(
                new Token(Token::SYMBOL, 'foo'),
                new Token(Token::SYMBOL, '.*+!-_?$%&=#:'),
                new Token(Token::SYMBOL, 'foo.bar/baz')
            )
        );
    }

    public function testLteralsCanBeRead()
    {
        $this->assertTokens(
            '"this is a \"string\""',
            array(
                new Token(Token::LITERAL, 'this is a "string"')
            )
        );
    }

    public function testMultiLineLiteralsCanBeRead()
    {
        $this->assertTokens(
            "\"foo\nbar\"",
            array(
                new Token(Token::LITERAL, "foo\nbar")
            )
        );
    }

    public function testCharactersCanBeRead()
    {
        $this->assertTokens(
            '\b',
            array(
                new Token(Token::CHARACTER, 'b')
            )
        );
    }

    public function testIntegersCanBeRead()
    {
        $this->assertTokens(
            '123',
            array(
                new Token(Token::NUMERIC, 123)
            )
        );
    }

    public function testFloatingPointsCanBeRead()
    {
        $this->assertTokens(
            '123.45',
            array(
                new Token(Token::NUMERIC, 123.45)
            )
        );
    }

    /**
     * @expectedException Edhen\Exception\TokenizerException
     */
    public function testExceptionThrownOnInvalidCharacter()
    {
        $tokenizer = new Tokenizer('£');
        $tokenizer->nextToken();
    }

    public function testListsCanBeRead()
    {
        $this->assertTokens(
            '(1 :foo bar)',
            array(
                new Token(Token::PAREN_OPEN),
                new Token(Token::NUMERIC, 1),
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::SYMBOL, 'bar'),
                new Token(Token::PAREN_CLOSE)
            )
        );

        $this->assertTokens(
            '(:foo)',
            array(
                new Token(Token::PAREN_OPEN),
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::PAREN_CLOSE)
            )
        );
    }

    public function testMapsCanBeRead()
    {
        $this->assertTokens(
            '{:foo 1, 2 bar}',
            array(
                new Token(Token::BRACE_OPEN),
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::NUMERIC, '1'),
                new Token(Token::NUMERIC, '2'),
                new Token(Token::SYMBOL, 'bar'),
                new Token(Token::BRACE_CLOSE)
            )
        );
    }

    public function testVectorsCanBeRead()
    {
        $this->assertTokens(
            '[1 :foo bar]',
            array(
                new Token(Token::SQUARE_OPEN),
                new Token(Token::NUMERIC, '1'),
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::SYMBOL, 'bar'),
                new Token(Token::SQUARE_CLOSE)
            )
        );
    }

    public function testSetsCanBeRead()
    {
        $this->assertTokens(
            '#{1 :foo}',
            array(
                new Token(Token::TAG, '{'),
                new Token(Token::NUMERIC, '1'),
                new Token(Token::KEYWORD, ':foo'),
                new Token(Token::BRACE_CLOSE)
            )
        );
    }

    public function testBuiltinTagsCanBeRead()
    {
        $this->assertTokens(
            '#_foo',
            array(
                new Token(Token::TAG, '_'),
                new Token(Token::SYMBOL, 'foo')
            )
        );
    }

    public function testUserDefinedTagsCanBeRead()
    {
        $this->assertTokens(
            '#inst "abc"',
            array(
                new Token(Token::TAG, 'inst'),
                new Token(Token::LITERAL, 'abc')
            )
        );
    }

    public function testCommentsAreRespected()
    {
        $this->assertTokens(
            "foo;ignore\n" .
            ";bar\n" .
            "baz",
            array(
                new Token(Token::SYMBOL, 'foo'),
                new Token(Token::SYMBOL, 'baz')
            )
        );
    }
}

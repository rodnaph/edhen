<?php

namespace Edhen\TagHandler;

use Edhen\Token;
use Edhen\Tokenizer;
use Edhen\Decoder;

class SetHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->handler = new SetHandler();
    }

    public function testCanHandle()
    {
        $this->assertTrue($this->handler->canHandle(new Token(Token::TAG, '{')));
        $this->assertFalse($this->handler->canHandle(new Token(Token::SYMBOL)));
    }

    public function testSetsCanBeDecoded()
    {
        $tokenizer = new Tokenizer(':foo [1 2]}');
        $decoder = new Decoder($tokenizer);

        $this->assertEquals(
            array(array('foo', array(1, 2))),
            $this->handler->decode($decoder)
        );
    }
}

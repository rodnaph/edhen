<?php

namespace Edhen\TagHandler;

use Edhen\Decoder;
use Edhen\Token;
use Edhen\Tokenizer;

class DiscardHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->handler = new DiscardHandler();
    }

    public function testCanHandle()
    {
        $this->assertTrue($this->handler->canHandle(new Token(Token::TAG, '_')));
        $this->assertFalse($this->handler->canHandle(new Token(Token::SYMBOL)));
    }

    public function testFollowingElementIsDiscarded()
    {
        $tokenizer = new Tokenizer('(1 2)');
        $decoder = new Decoder($tokenizer);

        $this->assertEmpty($this->handler->decode($decoder));
    }
}

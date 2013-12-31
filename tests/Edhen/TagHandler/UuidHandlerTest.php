<?php

namespace Edhen\TagHandler;

use Edhen\Decoder;
use Edhen\Token;
use Edhen\Tokenizer;

class UuidHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->handler = new UuidHandler();
    }

    public function testCanHandle()
    {
        $this->assertTrue($this->handler->canHandle(new Token(Token::SYMBOL, 'uuid')));
        $this->assertFalse($this->handler->canHandle(new Token(Token::HASH)));
    }

    public function testDecodingAUuid()
    {
        $tokenizer = new Tokenizer('"f81d4fae-7dec-11d0-a765-00a0c91e6bf6"');
        $decoder = new Decoder($tokenizer);

        $this->assertEquals(
            'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
            $this->handler->handle($decoder, $tokenizer)
        );
    }
}

<?php

use Edhen\Decoder;
use Edhen\TagHandler;
use Edhen\Token;

class EdhenTest extends PHPUnit_Framework_TestCase
{
    public function testDecodingSingleElements()
    {
        $this->assertEquals(null, Edhen::decode('nil'));
        $this->assertEquals(true, Edhen::decode('true'));
        $this->assertEquals(false, Edhen::decode('false'));
        $this->assertEquals('Hello, World',Edhen::decode('"Hello, World"'));
        $this->assertEquals("a", Edhen::decode('\a'));
        $this->assertEquals('foo', Edhen::decode('foo'));
        $this->assertEquals(':foo/bar', Edhen::decode(':foo/bar'));
        $this->assertEquals(123, Edhen::decode('123'));
        $this->assertEquals(123.45, Edhen::decode('123.45'));
        $this->assertEquals(array(1, ':foo'), Edhen::decode('(1 :foo)'));
        $this->assertEquals(array(':bar', 2), Edhen::decode('[:bar 2]'));
        $this->assertEquals(array(':baz' => 1), Edhen::decode('{:baz 1}'));
        $this->assertEquals(array(4, 5), Edhen::decode('#{4 5}'));
    }

    public function testDecodingMultipleElements()
    {
        $this->assertEquals(array(':foo', 1), Edhen::decodeAll(':foo 1'));
    }

    public function testCustomTagHandlersCanBeUsed()
    {
        $this->assertEquals(4, Edhen::decode('#double 2', array(new DoubleHandler())));
    }
}

class DoubleHandler implements TagHandler
{
    public function canHandle(Token $token)
    {
        return $token->getValue() == 'double';
    }

    public function decode(Decoder $decoder)
    {
        $element = $decoder->decode();

        return array($element * 2);
    }
}

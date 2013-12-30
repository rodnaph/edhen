<?php

class EdhenTest extends PHPUnit_Framework_TestCase
{
    public function testDataCanBeDecodedFromEdn()
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
}

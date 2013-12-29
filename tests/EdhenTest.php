<?php

class EdhenTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->edhen = new Edhen();
    }

    public function testDataCanBeEncodedToEdn()
    {
        return $this->markTestIncomplete();
    }

    public function testDataCanBeDecodedFromEdn()
    {
        $this->assertEquals(null, $this->edhen->decode('nil'));

        return $this->markTestIncomplete();

        $this->assertEquals(true, $this->edhen->decode('true'));
        $this->assertEquals(false, $this->edhen->decode('false'));
    }
}

<?php

namespace Edhen;

class EncoderTest extends \PHPUnit_Framework_TestCase
{
    protected function assertEncoding($expected, $data)
    {
        $encoder = new Encoder();
        $actual = $encoder->encode($data);

        $this->assertEquals($expected, $actual);
    }

    public function testNulls()
    {
        $this->assertEncoding('nil', null);
    }

    public function testBooleans()
    {
        $this->assertEncoding('true', true);
        $this->assertEncoding('false', false);
    }

    public function testStrings()
    {
        $this->assertEncoding('"foo\"bar"', "foo\"bar");
    }

    public function testIntegers()
    {
        $this->assertEncoding('123', 123);
    }

    public function testFloats()
    {
        $this->assertEncoding('123.45', 123.45);
    }

    public function testNumericArrays()
    {
        $this->assertEncoding('[1 [2 3] 4]', array(1, array(2, 3), 4));
    }

    public function testAssocArrays()
    {
        $this->assertEncoding('{"foo" [1 2] "bar" 2}', array('foo' => array(1, 2), 'bar' => 2));
    }

    public function testCallables()
    {
        $callable = function() {};

        $this->assertEncoding('nil', $callable);
    }

    public function testResources()
    {
        $fh = fopen(__FILE__, 'r');

        $this->assertEncoding('nil', $fh);
    }

    public function testObjects()
    {
        $object = new \stdclass();
        $object->foo = array(1, 2);

        $this->assertEncoding('{"foo" [1 2]}', $object);
    }
}

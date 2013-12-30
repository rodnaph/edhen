<?php

namespace Edhen;

class DecoderTest extends \PHPUnit_Framework_TestCase
{
    protected function assertDecoding($expected, $edn, $debug = false)
    {
        $tokenizer = new Tokenizer($edn);
        $decoder = new Decoder($tokenizer);
        $decoder->setDebug($debug);

        $this->assertEquals($expected, $decoder->decode());
    }

    public function testBooleansAreDecoded()
    {
        $this->assertDecoding(true, 'true');
        $this->assertDecoding(false, 'false');
    }

    public function testNilsAreDecoded()
    {
        $this->assertDecoding(null, 'nil');
    }

    public function testKeywordsAreDecodedToStrings()
    {
        $this->assertDecoding(':foo', ':foo');
    }

    public function testCharactersAreDecodedToStrings()
    {
        $this->assertDecoding('a', '\a');
    }

    public function testSymbolsAreDecodedToStrings()
    {
        $this->assertDecoding('abc', 'abc');
    }

    public function testListsAreDecodedToArrays()
    {
        $this->assertDecoding(array(':foo'), '(:foo)');
        $this->assertDecoding(array(1, 2), '(1 2)');
        $this->assertDecoding(array(1, 2), '(1, 2)');
    }

    public function testVectorsAreDecodedToArrays()
    {
        $this->assertDecoding(array(':foo'), '[:foo]');
        $this->assertDecoding(array(1, 2), '[1 2]');
        $this->assertDecoding(array(1, 2), '[1, 2]');
    }

    public function testMapsAreDecodedToAssocArrays()
    {
        $this->assertDecoding(array(':foo' => 123), '{:foo 123}');
    }

    public function testDecodingEmbeddedDataStructures()
    {
        $this->assertDecoding(array(':foo' => array(1, 2, 3)), '{:foo [1 2 3]}');
    }
}

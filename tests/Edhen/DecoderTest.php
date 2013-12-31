<?php

namespace Edhen;

class DecoderTest extends \PHPUnit_Framework_TestCase
{

    protected function decoder($edn, $debug = false)
    {
        $tokenizer = new Tokenizer($edn);
        $tokenizer->setDebug($debug);
        $decoder = new Decoder($tokenizer);

        return $decoder;
    }

    protected function assertDecode($expected, $edn, $debug = false)
    {
        $this->assertEquals($expected, $this->decoder($edn, $debug)->decode());
    }

    protected function assertDecodeAll($expected, $edn, $debug = false)
    {
        $this->assertEquals($expected, $this->decoder($edn, $debug)->decodeAll());
    }

    public function testBooleansAreDecoded()
    {
        $this->assertDecode(true, 'true');
        $this->assertDecode(false, 'false');
    }

    public function testNilsAreDecoded()
    {
        $this->assertDecode(null, 'nil');
    }

    public function testKeywordsAreDecodedToStrings()
    {
        $this->assertDecode(':foo', ':foo');
    }

    public function testCharactersAreDecodedToStrings()
    {
        $this->assertDecode('a', '\a');
    }

    public function testSymbolsAreDecodedToStrings()
    {
        $this->assertDecode('abc', 'abc');
    }

    public function testListsAreDecodedToArrays()
    {
        $this->assertDecode(array(':foo'), '(:foo)');
        $this->assertDecode(array(1, 2), '(1 2)');
        $this->assertDecode(array(1, 2), '(1, 2)');
    }

    public function testVectorsAreDecodedToArrays()
    {
        $this->assertDecode(array(':foo'), '[:foo]');
        $this->assertDecode(array(1, 2), '[1 2]');
        $this->assertDecode(array(1, 2), '[1, 2]');
    }

    public function testMapsAreDecodedToAssocArrays()
    {
        $this->assertDecode(array(':foo' => 123), '{:foo 123}');
    }

    public function testDecodingEmbeddedDataStructures()
    {
        $this->assertDecode(array(':foo' => array(1, 2, 3)), '{:foo [1 2 3]}');
    }

    public function testSetsAreDecodedToArrays()
    {
        $this->assertDecode(array(1, ':foo'), '#{1 :foo}');
    }

    public function testMultipleEntitiesCanBeDecoded()
    {
        $this->assertDecodeAll(array(':foo', array(1, 2)), ':foo [1 2]');
    }

    public function testDecodingCanStopAtATerminalTokenType()
    {
        $this->assertEquals(
            array(':foo'),
            $this->decoder(':foo [1 2]')->decodeAll(Token::SQUARE_OPEN)
        );
    }
}

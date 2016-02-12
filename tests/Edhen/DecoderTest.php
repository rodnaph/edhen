<?php

namespace Edhen;

use DateTime;

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
        $actual = $this->decoder($edn, $debug)->decode();
        $this->assertEquals($expected, $actual);
    }

    protected function assertDecodeAll($expected, $edn, $debug = false)
    {
        $actual = $this->decoder($edn, $debug)->decodeAll();
        $this->assertEquals($expected, $actual);
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
        $this->assertDecode('foo', ':foo');
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
        $this->assertDecode(array('foo'), '(:foo)');
        $this->assertDecode(array(1, 2), '(1 2)');
        $this->assertDecode(array(1, 2), '(1, 2)');
    }

    public function testVectorsAreDecodedToArrays()
    {
        $this->assertDecode(array('foo'), '[:foo]');
        $this->assertDecode(array(1, 2), '[1 2]');
        $this->assertDecode(array(1, 2), '[1, 2]');
    }

    public function testMapsAreDecodedToAssocArrays()
    {
        $this->assertDecode(array('foo' => 123), '{:foo 123}');
    }

    public function testDecodingEmbeddedDataStructures()
    {
        $this->assertDecode(array('foo' => array(1, 2, 3)), '{:foo [1 2 3]}');
    }

    public function testSetsAreDecodedToArrays()
    {
        $this->assertDecode(array(1, 'foo'), '#{1 :foo}');
    }

    public function testMultipleEntitiesCanBeDecoded()
    {
        $this->assertDecodeAll(array('foo', array(1, 2)), ':foo [1 2]');
    }

    public function testDecodingCanStopAtATerminalTokenType()
    {
        $this->assertEquals(
            array('foo'),
            $this->decoder('foo [1 2]')->decodeAll(Token::SQUARE_OPEN)
        );
    }

    public function testDecodingCanDiscardElements()
    {
        $this->assertDecode(array(1, 3), '[1 #_foo 3]');
    }

    public function testInstsAreDecoded()
    {
        $inst = '1996-12-19T16:39:57+00:00';

        $this->assertDecode(
            new DateTime('1996-12-19 16:39:57+00:00'),
            "#inst \"$inst\""
        );
    }

    public function testUuidsAreDecoded()
    {
        $uuid = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';

        $this->assertDecode(
            $uuid,
            "#uuid \"$uuid\""
        );
    }

    /**
     * @expectedException Edhen\Exception\UnknownTagException
     */
    public function testExceptionThrownWhenUnknownTagEncountered()
    {
        $this->assertDecode(null, '#unknown 1');
    }
}

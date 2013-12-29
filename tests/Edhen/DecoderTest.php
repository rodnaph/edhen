<?php

namespace Edhen;

class DecoderTest extends \PHPUnit_Framework_TestCase
{
    protected function assertDecoding($expected, $edn)
    {
        $tokenizer = new Tokenizer($edn);
        $decoder = new Decoder($tokenizer);

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

    public function testListsAreDecodedToArrays()
    {
        $this->assertDecoding(array(':foo'), '(:foo)');
    }
}

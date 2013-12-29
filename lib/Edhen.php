<?php

use Edhen\Tokenizer;
use Edhen\Decoder;

class Edhen
{
    public function encode($data)
    {
    }

    public function decode($edn)
    {
        $tokenizer = new Tokenizer($edn);
        $decoder = new Decoder($tokenizer);

        return $decoder->decode();
    }
}

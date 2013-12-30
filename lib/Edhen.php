<?php

use Edhen\Tokenizer;
use Edhen\Decoder;

class Edhen
{
    /**
     * @param string $edn
     *
     * @return mixed
     */
    public static function decode($edn)
    {
        $tokenizer = new Tokenizer($edn);
        $decoder = new Decoder($tokenizer);

        return $decoder->decode();
    }
}

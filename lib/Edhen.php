<?php

use Edhen\Tokenizer;

class Edhen
{
    public function encode($data)
    {
    }

    public function decode($edn)
    {
        $tokenizer = new Tokenizer($edn);

        return null;
    }
}

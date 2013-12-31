<?php

namespace Edhen;

interface TagHandler
{
    /**
     * @param Token $token
     *
     * @return boolean
     */
    public function canHandle(Token $token);

    /**
     * @param Tokenizer $tokenizer
     *
     * @return mixed
     */
    public function handle(Decoder $decoder, Tokenizer $tokenizer);
}

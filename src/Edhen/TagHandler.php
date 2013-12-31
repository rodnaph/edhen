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
     * Return an array of decoded elements
     *
     * @param Decoder $decoder
     *
     * @return array
     */
    public function decode(Decoder $decoder);
}

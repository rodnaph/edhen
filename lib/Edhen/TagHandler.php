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
     * @param Decoder $decoder
     *
     * @return mixed
     */
    public function decode(Decoder $decoder);
}

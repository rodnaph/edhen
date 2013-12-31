<?php

namespace Edhen\TagHandler;

use Edhen\Decoder;
use Edhen\TagHandler;
use Edhen\Token;

class SetHandler implements TagHandler
{
    /**
     * {@inheritDoc}
     */
    public function canHandle(Token $token)
    {
        return $token->getType() == Token::BRACE_OPEN;
    }

    /**
     * {@inheritDoc}
     */
    public function decode(Decoder $decoder)
    {
        return $decoder->decodeAll(Token::BRACE_CLOSE);
    }
}

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
        return $token->getType() == Token::TAG
            && $token->getValue() == '{';
    }

    /**
     * {@inheritDoc}
     */
    public function decode(Decoder $decoder)
    {
        return array(
            $decoder->decodeAll(Token::BRACE_CLOSE)
        );
    }
}

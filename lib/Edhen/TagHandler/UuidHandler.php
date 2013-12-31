<?php

namespace Edhen\TagHandler;

use Edhen\Decoder;
use Edhen\TagHandler;
use Edhen\Token;
use Edhen\Tokenizer;

class UuidHandler implements TagHandler
{
    /**
     * {@inheritDoc}
     */
    public function canHandle(Token $token)
    {
        return $token->getType() == Token::SYMBOL
            && $token->getValue() == 'uuid';
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Decoder $decoder, Tokenizer $tokenizer)
    {
        $token = $tokenizer->nextToken();

        return $token->getValue();
    }
}

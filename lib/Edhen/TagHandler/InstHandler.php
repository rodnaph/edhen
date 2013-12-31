<?php

namespace Edhen\TagHandler;

use DateTime;
use Edhen\Decoder;
use Edhen\TagHandler;
use Edhen\Token;
use Edhen\Tokenizer;

class InstHandler implements TagHandler
{
    /**
     * {@inheritDoc}
     */
    public function canHandle(Token $token)
    {
        return $token->getType() == Token::SYMBOL
            && $token->getValue() == 'inst';
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Decoder $decoder, Tokenizer $tokenizer)
    {
        $token = $tokenizer->nextToken(Token::LITERAL);

        return DateTime::createFromFormat(DateTime::RFC3339, $token->getValue());
    }
}

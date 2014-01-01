<?php

namespace Edhen\TagHandler;

use Edhen\Decoder;
use Edhen\TagHandler;
use Edhen\Token;

abstract class BaseHandler implements TagHandler
{
    /**
     * {@inheritDoc}
     */
    public function canHandle(Token $token)
    {
        return $token->getType() == Token::TAG
            && $token->getValue() == $this->getTagValue();
    }

    /**
     * {@inheritDoc}
     */
    public function decode(Decoder $decoder)
    {
        return $this->convert(
            $decoder->decode()
        );
    }

    /**
     * @param mixed $element
     *
     * @return array
     */
    protected function convert($element)
    {
        return array($element);
    }

    /**
     * @return string
     */
    abstract protected function getTagValue();
}

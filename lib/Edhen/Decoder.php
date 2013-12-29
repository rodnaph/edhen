<?php

namespace Edhen;

class Decoder
{
    /**
     * @param Tokenizer $tokenizer
     */
    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @return mixed
     */
    public function decode()
    {
        $token = $this->nextToken();

        return $this->decodeToken($token);
    }

    protected function decodeToken(Token $token)
    {
        switch ($token->getType()) {
            case Token::BOOLEAN_TRUE:
                return true;

            case Token::BOOLEAN_FALSE:
                return false;

            case Token::PAREN_OPEN:
                return $this->decodeList();

            default:
                return $token->getValue();
        }
    }

    /**
     * @return array
     */
    protected function decodeList()
    {
        $list = array();

        for ($token = $this->nextToken(); $token->getType() != Token::PAREN_CLOSE; $token = $this->nextToken()) {
            $list[] = $this->decodeToken($token);
        }

        return $list;
    }

    /**
     * @return Token|null
     */
    protected function nextToken()
    {
        return $this
            ->tokenizer
            ->nextToken();
    }
}

<?php

namespace Edhen;

class Decoder
{
    /**
     * @var boolean
     */
    private $debug;

    /**
     * @param Tokenizer $tokenizer
     */
    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->debug = false;
    }

    /**
     * @param boolean $debug
     *
     * @return Decoder
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function decode()
    {
        $token = $this->nextToken();

        return $this->decodeToken($token);
    }

    /**
     * @param Token $token
     *
     * @return mixed
     */
    protected function decodeToken(Token $token)
    {
        switch ($token->getType()) {
            case Token::BOOLEAN_TRUE:
                return true;

            case Token::BOOLEAN_FALSE:
                return false;

            case Token::PAREN_OPEN:
                return $this->decodeList(Token::PAREN_CLOSE);

            case Token::SQUARE_OPEN:
                return $this->decodeList(Token::SQUARE_CLOSE);

            case Token::BRACE_OPEN:
                return $this->decodeMap();

            default:
                return $token->getValue();
        }
    }

    /**
     * @return array
     */
    protected function decodeList($terminalToken)
    {
        $list = array();

        while ($token = $this->nextToken()) {
            if ($token->getType() == $terminalToken) {
                break;
            }

            $list[] = $this->decodeToken($token);
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function decodeMap()
    {
        $map = array();

        while (true) {
            $keyToken = $this->nextToken();

            if ($keyToken->getType() == Token::BRACE_CLOSE) {
                return $map;
            }

            $valueToken = $this->nextToken();

            $map[$keyToken->getValue()] = $valueToken->getValue();
        }
    }

    /**
     * @return Token|null
     */
    protected function nextToken()
    {
        $token = $this
            ->tokenizer
            ->nextToken();

        if ($this->debug) {
            if ($token) {
                echo sprintf(
                    "Token: %d, %s\n",
                    $token->getType(),
                    $token->getValue()
                );
            } else {
                echo "NO TOKEN\n";
            }
        }

        return $token;
    }
}

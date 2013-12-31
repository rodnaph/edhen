<?php

namespace Edhen;

use Edhen\TagHandler\SetHandler;

class Decoder
{
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var array
     */
    private $tagHandlers;

    /**
     * @param Tokenizer $tokenizer
     */
    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->tagHandlers = array(
            new SetHandler()
        );
    }

    /**
     * @return mixed
     */
    public function decode()
    {
        $token = $this->tokenizer->nextToken();

        return $this->decodeToken($token);
    }

    /**
     * @return array
     */
    public function decodeAll($terminalType = null)
    {
        $list = array();

        while (true) {
            $token = $this->tokenizer->nextToken();

            if (!$token) {
                return $list;
            }

            if ($token->getType() == $terminalType) {
                return $list;
            }

            $list[] = $this->decodeToken($token);
        }

        return $list;
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
                return $this->decodeAll(Token::PAREN_CLOSE);

            case Token::SQUARE_OPEN:
                return $this->decodeAll(Token::SQUARE_CLOSE);

            case Token::HASH:
                return $this->decodeTaggedElement();

            case Token::BRACE_OPEN:
                return $this->decodeMap();

            default:
                return $token->getValue();
        }
    }

    /**
     * @return array
     */
    protected function decodeMap()
    {
        $map = array();

        while (true) {
            $keyToken = $this->tokenizer->nextToken();

            if ($keyToken->getType() == Token::BRACE_CLOSE) {
                return $map;
            }

            $valueToken = $this->tokenizer->nextToken();
            $key = $this->decodeToken($keyToken);
            $value = $this->decodeToken($valueToken);

            $map[$key] = $value;
        }
    }

    /**
     * @return mixed
     */
    protected function decodeTaggedElement()
    {
        $token = $this->tokenizer->nextToken();

        foreach ($this->tagHandlers as $handler) {
            if ($handler->canHandle($token)) {
                return $handler->handle($this, $this->tokenizer);
            }
        }
    }
}

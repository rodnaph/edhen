<?php

namespace Edhen;

use Edhen\Exception\UnknownTagException;
use Edhen\TagHandler\InstHandler;
use Edhen\TagHandler\SetHandler;
use Edhen\TagHandler\UuidHandler;
use Edhen\TagHandler\DiscardHandler;

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
        $this->tagHandlers = array();

        $this->addDefaultTagHandlers();
    }

    /**
     * @param TagHandler $tagHandler
     *
     * @return Decoder
     */
    public function addTagHandler(TagHandler $tagHandler)
    {
        $this->tagHandlers[] = $tagHandler;
    }

    /**
     * @return mixed
     */
    public function decode()
    {
        $token = $this->tokenizer->nextToken();
        $tokens = $this->decodeToken($token);

        return empty($tokens)
            ? null
            : $tokens[0];
    }

    /**
     * @param string $terminalType
     *
     * @return array
     */
    public function decodeAll($terminalType = null)
    {
        $list = array();

        while (true) {
            $token = $this->tokenizer->nextToken();

            if (!$token || $token->getType() == $terminalType) {
                return $list;
            }

            $tokens = $this->decodeToken($token);

            $list = array_merge($list, $tokens);
        }
    }

    /**
     * Override to customize tag handlers
     */
    protected function addDefaultTagHandlers()
    {
        $this->addTagHandler(new SetHandler());
        $this->addTagHandler(new InstHandler());
        $this->addTagHandler(new UuidHandler());
        $this->addTagHandler(new DiscardHandler());
    }

    /**
     * @param Token $token
     *
     * @return array
     */
    protected function decodeToken(Token $token)
    {
        switch ($token->getType()) {
            case Token::BOOLEAN_TRUE:
                return array(true);

            case Token::BOOLEAN_FALSE:
                return array(false);

            case Token::PAREN_OPEN:
                return array($this->decodeAll(Token::PAREN_CLOSE));

            case Token::SQUARE_OPEN:
                return array($this->decodeAll(Token::SQUARE_CLOSE));

            case Token::TAG:
                return $this->decodeTaggedElement($token);

            case Token::BRACE_OPEN:
                return array($this->decodeMap());

            default:
                return array($token->getValue());
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

            $keys = $this->decodeToken($keyToken);
            $key = $keys[0];

            $values = $this->decodeToken($valueToken);
            $value = $values[0];

            $map[$key] = $value;
        }
    }

    /**
     * @return array
     *
     * @throws UnknownTagException
     */
    protected function decodeTaggedElement(Token $token)
    {
        foreach ($this->tagHandlers as $handler) {
            if ($handler->canHandle($token)) {
                return $handler->decode($this);
            }
        }

        throw new UnknownTagException($token);
    }
}

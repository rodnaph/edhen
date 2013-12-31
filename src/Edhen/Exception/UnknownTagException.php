<?php

namespace Edhen\Exception;

use Edhen\Token;

class UnknownTagException extends \RuntimeException
{
    /**
     * @var Token
     */
    private $token;

    /**
     * @param Token $token
     */
    public function __construct(Token $token)
    {
        parent::__construct(
            sprintf(
                'Unknown tag: %s',
                $token->getValue()
            )
        );

        $this->token = $token;
    }
}

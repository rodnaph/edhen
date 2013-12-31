<?php

namespace Edhen\Exception;

use Edhen\Token;

class UnexpectedTokenException extends \RuntimeException
{
    /**
     * @param string $expectedType
     * @param Token $token
     */
    public function __construct($expectedType, Token $token = null)
    {
        parent::__construct(
            sprintf(
                'Expected "%s", but got "%s"',
                $expectedType,
                $token ? $token->getType() : 'NULL'
            )
        );
    }
}

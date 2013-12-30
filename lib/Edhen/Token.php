<?php

namespace Edhen;

class Token
{
    /**
     * @var integer
     */
    const BRACE_OPEN = 1;

    /**
     * @var integer
     */
    const BRACE_CLOSE = 2;

    /**
     * @var integer
     */
    const KEYWORD = 3;

    /**
     * @var integer
     */
    const NUMERIC = 4;

    /**
     * @var integer
     */
    const BOOLEAN_TRUE = 5;

    /**
     * @var integer
     */
    const BOOLEAN_FALSE = 6;

    /**
     * @var integer
     */
    const LITERAL = 7;

    /**
     * @var integer
     */
    const NIL = 8;

    /**
     * @var integer
     */
    const SYMBOL = 9;

    /**
     * @var integer
     */
    const CHARACTER = 10;

    /**
     * @var integer
     */
    const PAREN_OPEN = 11;

    /**
     * @var integer
     */
    const PAREN_CLOSE = 12;

    /**
     * @var integer
     */
    const SQUARE_OPEN = 13;

    /**
     * @var integer
     */
    const SQUARE_CLOSE = 14;

    /**
     * @var integer
     */
    const HASH = 15;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @param integer $type
     * @param string $value
     */
    public function __construct($type, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

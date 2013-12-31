<?php

namespace Edhen;

class Token
{
    /**
     * @var string
     */
    const BRACE_OPEN = 'brace_open';

    /**
     * @var string
     */
    const BRACE_CLOSE = 'brace_close';

    /**
     * @var string
     */
    const KEYWORD = 'keyword';

    /**
     * @var string
     */
    const NUMERIC = 'numeric';

    /**
     * @var string
     */
    const BOOLEAN_TRUE = 'boolean_true';

    /**
     * @var string
     */
    const BOOLEAN_FALSE = 'boolean_false';

    /**
     * @var string
     */
    const LITERAL = 'literal';

    /**
     * @var string
     */
    const NIL = 'nil';

    /**
     * @var string
     */
    const SYMBOL = 'symbol';

    /**
     * @var string
     */
    const CHARACTER = 'character';

    /**
     * @var string
     */
    const PAREN_OPEN = 'paren_open';

    /**
     * @var string
     */
    const PAREN_CLOSE = 'paren_close';

    /**
     * @var string
     */
    const SQUARE_OPEN = 'square_open';

    /**
     * @var string
     */
    const SQUARE_CLOSE = 'square_close';

    /**
     * @var string
     */
    const HASH = 'hash';

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

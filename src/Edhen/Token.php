<?php

namespace Edhen;

use Edhen\Exception\TokenizerException;

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
    const TAG = 'tag';

    /**
     * @var string
     */
    const COMMENT = 'comment';

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

        $this->validateSymbolSlashes();
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

    /**
     * Validate a symbol contains up to one forward slash, and that either it is either a single
     * slash character or it separates a prefix and name parts. See https://github.com/edn-format/edn#symbols
     */
    private function validateSymbolSlashes()
    {
        if ($this->type !== Token::SYMBOL) {
            return;
        }

        if (substr_count($this->value, "/") > 1) {
            throw new TokenizerException("Invalid symbol '$this->value': Symbols can only contain zero or one slashes");
        }

        $slashPosition = strpos($this->value, "/");
        if ($slashPosition !== false) {
            if (strlen($this->value) > 1) {
                if ($slashPosition == 0) {
                    throw new TokenizerException("Invalid symbol '$this->value': Symbols cannot start with a slash");
                }
                if ($slashPosition == strlen($this->value) - 1) {
                    throw new TokenizerException("Invalid symbol '$this->value': Symbols cannot end with a slash");
                }
            }
        }
    }
}

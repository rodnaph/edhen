<?php

namespace Edhen;

class Tokenizer
{
    /**
     * @var string
     */
    private $edn;

    /**
     * @var integer
     */
    private $position;

    /**
     * @param string $edn
     */
    public function __construct($edn)
    {
        $this->edn = $edn;
        $this->position = 0;
    }

    /**
     * @return Token|null
     */
    public function nextToken()
    {
        $type = null;
        $value = '';

        while ($c = $this->nextChar()) {

            switch ($type) {

                case Token::KEYWORD:
                    if (!$this->isWhitespace($c)) {
                        $value .= $c;
                    } else {
                        break 2;
                    }
                    break;

                case Token::NUMERIC:
                    if ($this->isNumeric($c)) {
                        $value .= $c;
                    } else {
                        $this->backtrack();
                        break 2;
                    }
                    break;

                default:
                    if ($this->isNumeric($c)) {
                        $type = Token::NUMERIC;
                        $value = $c;
                    } elseif ($c == '{') {
                        return new Token(Token::BRACE_OPEN);
                    } elseif ($c == '}') {
                        return new Token(Token::BRACE_CLOSE);
                    } elseif ($c == ':') {
                        $type = Token::KEYWORD;
                        $value = $c;
                    }

            }

        }

        if ($type) {
            return new Token($type, $value);
        }
    }

    /**
     * @return string
     */
    protected function nextChar()
    {
        return isset($this->edn[$this->position])
            ? $this->edn[$this->position++]
            : null;
    }

    /**
     *
     */
    protected function backtrack()
    {
        $this->position--;
    }

    /**
     * @param string $c
     *
     * @return boolean
     */
    protected function isWhitespace($c)
    {
        return $c == ' ';
    }

    /**
     * @param string $c
     *
     * return boolean
     */
    protected function isNumeric($c)
    {
        return preg_match('/^[0-9]$/', $c);
    }
}

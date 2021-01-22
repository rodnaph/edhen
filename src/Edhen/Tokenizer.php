<?php

namespace Edhen;

use Edhen\Exception\TokenizerException;
use Edhen\Exception\UnexpectedTokenException;

class Tokenizer
{
    private static $CHARACTERS = array(
        '{' => Token::BRACE_OPEN,
        '}' => Token::BRACE_CLOSE,
        '(' => Token::PAREN_OPEN,
        ')' => Token::PAREN_CLOSE,
        '[' => Token::SQUARE_OPEN,
        ']' => Token::SQUARE_CLOSE
    );

    /**
     * @var string
     */
    private $edn;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var boolean
     */
    private $debug;

    /**
     * @param string $edn
     */
    public function __construct($edn)
    {
        $this->edn = $edn;
        $this->position = 0;
        $this->debug = false;
    }

    /**
     * @param boolean $debug
     *
     * @return Tokenizer
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return Token|null
     */
    public function nextToken()
    {
        $type = null;
        $value = '';
        $b = null;

        while (($c = $this->nextChar()) !== null) {

            if ($this->debug) {
                echo "Char: $c\n";
            }

            switch ($type) {

                case Token::KEYWORD:
                    if ($this->isKeywordCharacter($c)) {
                        $value .= $c;
                        break;
                    } else {
                        $this->backtrack();
                        break 2;
                    }

                case Token::NUMERIC:
                    if ($this->isNumericCharacter($value, $c)) {
                        $value .= $c;
                        break;
                    } else {
                        $this->backtrack();
                        break 2;
                    }

                case Token::SYMBOL:
                    if ($this->isSymbolCharacter($c)) {
                        $value .= $c;
                        break;
                    } else {
                        $this->backtrack();
                        break 2;
                    }

                case Token::LITERAL:
                    if ($b == '\\') {
                        $value .= $this->unescape($c);
                        break;
                    } elseif ($c == '\\') {
                        break;
                    } elseif ($c != '"') {
                        $value .= $c;
                        break;
                    } else {
                        break 2;
                    }

                case Token::CHARACTER:
                    $value = $c;
                    break;

                case Token::TAG:
                    if ($this->isTagCharacter($c)) {
                        $value .= $c;
                        break;
                    } elseif (!$value) {
                        $value = $c;
                        break 2;
                    } else {
                        break 2;
                    }

                case Token::COMMENT:
                    if ($c == "\n") {
                        $type = null;
                    }
                    break;

                default:
                    foreach (static::$CHARACTERS as $char => $token) {
                        if ($c == $char) {
                            $type = $token;
                            break 3;
                        }
                    }

                    if ($this->isWhitespace($c)) {
                        continue 2;
                    } elseif ($c == ';') {
                        $type = Token::COMMENT;
                    } elseif ($c == '#') {
                        $type = Token::TAG;
                    } elseif ($c == '\\') {
                        $type = Token::CHARACTER;
                    } elseif ($c == ':') {
                        $type = Token::KEYWORD;
                        $value = $c;
                    } elseif ($c == '"') {
                        $type = Token::LITERAL;
                    } elseif ($this->isNumericStart($c)) {
                        $type = Token::NUMERIC;
                        $value = $c;
                    } elseif ($this->isSymbolStart($c)) {
                        $type = Token::SYMBOL;
                        $value = $c;
                    } else {
                        throw new TokenizerException(
                            sprintf('Unexpected character: %s', $c)
                        );
                    }

            }

            $b = $c;

        }

        if ($type !== null) {
            $token = ($type == Token::SYMBOL)
                ? $this->interpretSymbol($value)
                : new Token($type, $value);

            if ($this->debug) {
                echo sprintf(
                    "Token: %s '%s'\n",
                    $token->getType(),
                    $token->getValue()
                );
            }

            return $token;
        } else {
            if ($this->debug) {
                echo "END OF TOKENS\n";
            }
        }
    }

    /**
     * @param string $value
     *
     * @return Token
     */
    protected function interpretSymbol($value)
    {
        switch ($value) {
            case 'true':
                return new Token(Token::BOOLEAN_TRUE);
            case 'false':
                return new Token(Token::BOOLEAN_FALSE);
            case 'nil':
                return new Token(Token::NIL);
            default:
                return new Token(Token::SYMBOL, $value);
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
    protected function isSymbolStart($c)
    {
        return preg_match('/^[<>0-9a-z.*+!\-_?$%&=\/]+$/i', $c);
    }

    /**
     * @param string $c
     *
     * @return boolean
     */
    protected function isSymbolCharacter($c)
    {
        return preg_match('/^[0-9a-z.*+\/!\-_?$%&=#:]+$/i', $c);
    }

    /**
     * @param string $c
     *
     * @return boolean
     */
    protected function isKeywordCharacter($c)
    {
        return $c == '/' || $this->isSymbolCharacter($c);
    }

    /**
     * @param string $c
     *
     * @return boolean
     */
    protected function isWhitespace($c)
    {
        return preg_match('/^[\s,]+$/', $c);
    }

    /**
     * @param string $c
     *
     * return boolean
     */
    protected function isNumericStart($c)
    {
        return preg_match('/^[0-9]$/', $c);
    }

    /**
     * @param string $c
     *
     * return boolean
     */
    protected function isNumericCharacter($value, $c) {
        if ($c == "-" && substr($value, -1) == "E") {
            return true;
        } else if ($c == "E" && strpos($value, "E") === false) {
            return true;
        } else {
            return preg_match('/^[0-9\.]$/', $c);
        }
    }

    /**
     * @param string $c
     *
     * @return boolean
     */
    protected function isTagCharacter($c)
    {
        return preg_match('/^[a-z]$/i', $c);
    }

    /**
     * @param string $c
     *
     * @return string
     */
    protected function unescape($c)
    {
        switch ($c) {
            case '"': return '"';
            case 'r': return "\r";
            case 'n': return "\n";
            case 't': return "\t";
            default:
                throw new TokenizerException(
                    sprintf(
                        'Unsupported escape character: %s',
                        $c
                    )
                );
        }
    }
}

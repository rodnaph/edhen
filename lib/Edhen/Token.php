<?php

namespace Edhen;

class Token
{
    const BRACE_OPEN = 1;

    const BRACE_CLOSE = 2;

    const KEYWORD = 3;

    const NUMERIC = 4;

    private $type;

    private $value;

    public function __construct($type, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }
}

<?php

namespace Edhen;

use Closure;

class Encoder
{
    /**
     * @param boolean $boolean
     *
     * @return string
     */
    public static function encodeBoolean($boolean)
    {
        return $boolean ? 'true' : 'false';
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function encodeString($string)
    {
        $toString = static::toString();

        return $toString($string);
    }

    /**
     * @param integer|double $numeric
     *
     * @return string
     */
    public static function encodeNumeric($numeric)
    {
        return '' . $numeric;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public static function encodeArray(array $array)
    {
        return static::isAssocArray($array)
            ? static::encodeAssocArray($array)
            : static::encodeNumericArray($array);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function encode($data)
    {
        $encoder = static::getEncoder();

        return $encoder($data);
    }

    /**
     * @param callable $callable
     *
     * @return string
     */
    public static function encodeCallable($callable)
    {
        return 'nil';
    }

    /**
     * @param resource $resource
     *
     * @return string
     */
    public static function encodeResource($resource)
    {
        return 'nil';
    }

    /**
     * @return Closure
     */
    protected static function getEncoder()
    {
        return function ($data) {
            if (is_callable($data)) {
                return Encoder::encodeCallable($data);
            }

            switch (gettype($data)) {
                case 'NULL':
                    return 'nil';
                case 'boolean':
                    return Encoder::encodeBoolean($data);
                case 'string':
                    return Encoder::encodeString($data);
                case 'integer':
                case 'double':
                    return Encoder::encodeNumeric($data);
                case 'array':
                    return Encoder::encodeArray($data);
                case 'resource':
                    return Encoder::encodeResource($data);
            }
        };
    }

    /**
     * @return Closure
     */
    protected static function toString()
    {
        return function($string) {
            return sprintf(
                '"%s"',
                str_replace('"', '\\"', $string)
            );
        };
    }

    /**
     * @param array $array
     *
     * @return boolean
     */
    protected static function isAssocArray(array $array)
    {
        foreach (array_keys($array) as $key) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected static function encodeAssocArray(array $array)
    {
        $toString = static::toString();
        $keys = array_map($toString, array_keys($array));
        $vals = array_values($array);

        return sprintf(
            '{%s}',
            implode(' ', $this->arrayZip($keys, $vals))
        );
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected static function encodeNumericArray(array $array)
    {
        return sprintf(
            '[%s]',
            implode(' ', array_values($array))
        );
    }

    /**
     * @param array args...
     *
     * @return array
     */
    protected static function arrayZip()
    {
        $output = array();

        for ($args = func_get_args(); count($args); $args = array_filter($args)) {
            foreach ($args as &$arg) {
                $output[] = array_shift($arg);
            }
        }

        return $output;
    }
}

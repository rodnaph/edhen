<?php

namespace Edhen;

use Closure;

class Encoder
{
    /**
     * @return string
     */
    public static function encodeNull()
    {
        return 'nil';
    }

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
        return sprintf(
            '"%s"',
            str_replace('"', '\\"', $string)
        );
    }

    /**
     * @param integer|double $numeric
     *
     * @return string
     */
    public static function encodeNumeric($numeric)
    {
        return $numeric;
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
     * @param object $object
     *
     * @return string
     */
    public static function encodeObject($object)
    {
        $vars = get_object_vars($object);

        return static::encodeAssocArray($vars);
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
                    return Encoder::encodeNull();
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
                case 'object':
                    return Encoder::encodeObject($data);
            }
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
        $encoder = static::getEncoder();
        $keys = array_map($encoder, array_keys($array));
        $vals = array_map($encoder, array_values($array));
        $zipd = Encoder::arrayZip($keys, $vals);

        return sprintf(
            '{%s}',
            implode(' ', $zipd)
        );
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected static function encodeNumericArray(array $array)
    {
        $encoder = static::getEncoder();
        $vals = array_map($encoder, $array);

        return sprintf(
            '[%s]',
            implode(' ', $vals)
        );
    }

    /**
     * Zip multiple arrays together - via stackoverflow :)
     *
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

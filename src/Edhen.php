<?php

use Edhen\Tokenizer;
use Edhen\Decoder;

class Edhen
{
    /**
     * @param string $edn
     * @param array $tagHandlers
     *
     * @return mixed
     */
    public static function decode($edn, array $tagHandlers = array())
    {
        return static::run('decode', $edn, $tagHandlers);
    }

    /**
     * @param string $edn
     * @param array $tagHandlers
     *
     * @return array
     */
    public static function decodeAll($edn, array $tagHandlers = array())
    {
        return static::run('decodeAll', $edn, $tagHandlers);
    }

    /**
     * @param string $edn
     * @param array $tagHandlers
     *
     * @return mixed
     */
    protected static function run($method, $edn, array $tagHandlers)
    {
        $tokenizer = new Tokenizer($edn);
        $decoder = new Decoder($tokenizer);

        foreach ($tagHandlers as $tagHandler) {
            $decoder->addTagHandler($tagHandler);
        }

        return $decoder->$method();
    }
}

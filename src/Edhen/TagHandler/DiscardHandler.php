<?php

namespace Edhen\TagHandler;

class DiscardHandler extends BaseHandler
{
    /**
     * {@inheritDoc}
     */
    protected function getTagValue()
    {
        return '_';
    }

    /**
     * {@inheritDoc}
     */
    protected function convert($element)
    {
        return array();
    }
}

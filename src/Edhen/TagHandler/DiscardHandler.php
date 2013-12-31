<?php

namespace Edhen\TagHandler;

class DiscardHandler extends BaseHandler
{
    protected function getTagValue()
    {
        return '_';
    }

    protected function convert($data)
    {
        return array();
    }
}

<?php

namespace Edhen\TagHandler;

use DateTime;

class InstHandler extends BaseHandler
{
    /**
     * {@inheritDoc}
     */
    public function getTagValue()
    {
        return 'inst';
    }

    /**
     * {@inheritDoc}
     */
    public function convert($data)
    {
        return DateTime::createFromFormat(DateTime::RFC3339, $data);
    }
}

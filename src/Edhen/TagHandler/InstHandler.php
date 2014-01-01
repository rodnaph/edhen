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
    public function convert($element)
    {
        return array(
            DateTime::createFromFormat(DateTime::RFC3339, $element)
        );
    }
}

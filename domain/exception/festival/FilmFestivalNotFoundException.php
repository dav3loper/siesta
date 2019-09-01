<?php
namespace siesta\domain\exception\festival;

use siesta\domain\exception\DataNotFoundException;

class FilmFestivalNotFoundException extends DataNotFoundException
{
    private const TYPE = 'festival';

    /**
     * @return string
     */
    protected function _getDataType(): string
    {
        return self::TYPE;
    }
}
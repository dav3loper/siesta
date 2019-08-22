<?php
namespace siesta\domain\exception;

class MovieNotFoundException extends DataNotFoundException
{
    /**
     * @return string
     */
    private const TYPE = 'Movie';

    /**
     * @return string
     */
    protected function _getDataType(): string
    {
        return self::TYPE;
    }
}
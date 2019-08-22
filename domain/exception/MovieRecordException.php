<?php
namespace siesta\domain\exception;

/**
 * Class MovieRecordException
 * @package siesta\domain\exception
 */
class MovieRecordException extends RecordException
{
    private const TYPE = 'movie';

    /**
     * @return string
     */
    protected function _getDataType(): string
    {
        return self::TYPE;
    }
}
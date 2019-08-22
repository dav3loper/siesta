<?php
namespace siesta\domain\exception\vote;

use siesta\domain\exception\RecordException;

class VoteRecordException extends RecordException
{
    private const TYPE = 'vote';

    /**
     * @return string
     */
    protected function _getDataType(): string
    {
        return self::TYPE;
    }
}
<?php
namespace siesta\domain\exception\user;

use siesta\domain\exception\RecordException;

class UserRecordException extends RecordException
{
    private const TYPE = 'user';

    /**
     * @return string
     */
    protected function _getDataType(): string
    {

        return self::TYPE;
    }
}
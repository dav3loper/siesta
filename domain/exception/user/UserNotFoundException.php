<?php
namespace siesta\domain\exception\user;

use siesta\domain\exception\DataNotFoundException;

class UserNotFoundException extends DataNotFoundException
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
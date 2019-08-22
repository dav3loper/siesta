<?php
namespace siesta\domain\exception\vote;

use siesta\domain\exception\DataNotFoundException;

class VoteNotFoundException extends DataNotFoundException
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
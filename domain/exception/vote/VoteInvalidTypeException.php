<?php
namespace siesta\domain\exception\vote;

use siesta\domain\exception\InvalidTypeException;

/**
 * Class VoteInvalidTypeException
 * @package siesta\domain\exception\vote
 */
class VoteInvalidTypeException extends InvalidTypeException
{
    const TYPE = 'vote';

    /**
     * @return string
     */
    protected function _getType(): string
    {
        return self::TYPE;
    }
}
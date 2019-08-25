<?php
namespace siesta\domain\user\infrastructure;

use siesta\domain\exception\user\UserRecordException;
use siesta\domain\user\User;

/**
 * Interface UserRecorder
 * @package siesta\domain\user\infrastructure
 */
interface UserRecorder
{
    /**
     * @param User $user
     * @throws UserRecordException
     */
    public function store(User $user);
}
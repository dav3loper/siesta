<?php
namespace siesta\domain\user\infrastructure;

use siesta\domain\exception\user\UserNotFoundException;
use siesta\domain\user\User;

/**
 * Interface UserProvider
 * @package siesta\domain\user\infrastructure
 */
interface UserProvider
{
    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function byEmail(string $email): User;
}
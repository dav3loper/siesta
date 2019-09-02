<?php
namespace siesta\domain\user\infrastructure;

use siesta\domain\user\User;

/**
 * Interface UserProvider
 * @package siesta\domain\user\infrastructure
 */
interface UserProvider
{

    /**
     * @return User[]
     */
    public function findAll(): array;
}
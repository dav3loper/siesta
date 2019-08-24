<?php
namespace siesta\infrastructure\user\persistence;

use siesta\domain\exception\user\UserRecordException;
use siesta\domain\user\infrastructure\UserRecorder;
use siesta\domain\user\User;

//TODO: hacer test

/**
 * Class EloquentUserRecorder
 * @package siesta\infrastructure\user\persistence
 */
class EloquentUserRecorder extends EloquentUserRepository implements UserRecorder
{

    /**
     * @param User $user
     * @throws UserRecordException
     */
    public function store(User $user): void
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            self::create($this->_getFillableFields($user));
        } catch (\Exception $e) {
            throw new UserRecordException($e);
        }
    }

    /**
     * @param User $user
     * @return array
     */
    private function _getFillableFields(User $user): array
    {
        return array_combine($this->fillable, [
            $user->getEmail(),
            $user->getPassword(),
            $user->getSalt()

        ]);
    }
}
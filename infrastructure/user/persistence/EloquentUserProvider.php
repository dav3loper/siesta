<?php
namespace siesta\infrastructure\user\persistence;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\user\UserNotFoundException;
use siesta\domain\user\infrastructure\UserProvider;
use siesta\domain\user\User;

/**
 * Class EloquentUserProvider
 * @package siesta\infrastructure\user\persistence
 */
class EloquentUserProvider extends EloquentUserRepository implements UserProvider
{


    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function byEmail(string $email): User
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentUserProvider $mapping */
            $mapping = self::where(self::EMAIL, '=', $email)->firstOrFail();

            return $this->_getUserFromMapping($mapping->getAttributes());
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($e);
        }
    }

    /**
     * @param array $attributes
     * @return User
     */
    private function _getUserFromMapping(array $attributes): User
    {
        $fields = self::FILLABLE_FIELDS;
        $fields[] = self::ID;
        $user = new User();
        $user->setEmail($attributes[self::EMAIL]);
        $user->setPassword($attributes[self::PASSWORD]);
        $user->setSalt($attributes[self::SALT]);
        $user->setId($attributes[self::ID]);

        return $user;
    }
}
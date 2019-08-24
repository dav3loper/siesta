<?php
namespace siesta\infrastructure\user\persistence;

use Illuminate\Database\Eloquent\Model;
use siesta\domain\exception\user\UserRecordException;
use siesta\domain\user\infrastructure\UserRecorder;
use siesta\domain\user\User;

//TODO: hacer test

/**
 * Class EloquentUserRecorder
 * @package siesta\infrastructure\user\persistence
 */
class EloquentUserRecorder extends Model implements UserRecorder
{

    private const TABLE_NAME = 'user';
    private const FILLABLE_FIELDS = ['email', 'password', 'salt'];

    public function __construct()
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        parent::__construct([]);
    }

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
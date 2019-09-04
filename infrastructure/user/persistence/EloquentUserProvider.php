<?php
namespace siesta\infrastructure\user\persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\user\infrastructure\UserProvider;
use siesta\domain\user\User;


class EloquentUserProvider extends Model implements UserProvider
{
    protected const TABLE_NAME = 'users';

    /**
     * EloquentUserProvider constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = self::TABLE_NAME;
        parent::__construct($attributes);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        try {
            $mappingList = self::all();

            return $this->_fromMappingToDomain($mappingList);
        } catch (ModelNotFoundException $e) {
            return [];
        }
    }

    /**
     * @param $mappingList
     * @return User[]
     */
    private function _fromMappingToDomain($mappingList): array
    {
        $userList = [];
        foreach ($mappingList as $mapping) {
            $user = User::build()
                ->setId($mapping->id)
                ->setEmail($mapping->email)
                ->setName($mapping->name);
            $userList[] = $user;
        }

        return $userList;
    }
}
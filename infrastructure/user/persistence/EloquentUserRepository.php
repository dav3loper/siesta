<?php
namespace siesta\infrastructure\user\persistence;

use Illuminate\Database\Eloquent\Model;

abstract class EloquentUserRepository extends Model
{
    protected const FILLABLE_FIELDS = [self::EMAIL, self::PASSWORD, self::SALT];
    protected const TABLE_NAME = 'user';
    protected const ID = 'id';
    protected const EMAIL = 'email';
    protected const PASSWORD = 'password';
    protected const SALT = 'salt';

    public function __construct()
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        parent::__construct([]);
    }
}
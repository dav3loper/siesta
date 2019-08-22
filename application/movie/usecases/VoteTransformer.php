<?php
namespace siesta\application\movie\usecases;

use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\vote\NonScore;
use siesta\domain\vote\Score;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;

class VoteTransformer
{
    private const TRANSFORMATIONS = [
        '0' => NonScore::class,
        '1' => WeakScore::class,
        '2' => StrongScore::class,
    ];

    /**
     * @param string $key
     * @return Score
     * @throws VoteInvalidTypeException
     */
    public static function fromValueToDomain($key): Score
    {
        if (array_key_exists($key, self::TRANSFORMATIONS)) {
            $className = self::TRANSFORMATIONS[$key];

            return $className::get();
        }
        throw new VoteInvalidTypeException($key);
    }
}
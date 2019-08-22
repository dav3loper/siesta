<?php
namespace siesta\infrastructure\vote\persistence;

use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\vote\NonScore;
use siesta\domain\vote\Score;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;

/**
 * Class EloquentScoreTransformer
 * @package siesta\infrastructure\vote\persistence
 */
class EloquentScoreTransformer implements ScoreTransformer
{

    private const TRANSFORMATIONS = [
        NonScore::class => 0,
        WeakScore::class => 1,
        StrongScore::class => 2,
    ];

    /**
     * @param Score $score
     * @return int
     * @throws VoteInvalidTypeException
     */
    public function fromDomainToPersistence(Score $score): int
    {
        $className = \get_class($score);
        if ($this->_existsDomainObject($className)) {
            return self::TRANSFORMATIONS[$className];
        }
        throw new VoteInvalidTypeException($className);
    }

    /**
     * @param $class
     * @return bool
     */
    private function _existsDomainObject($className): bool
    {
        return array_key_exists($className, self::TRANSFORMATIONS);
    }

    /**
     * @param int $score
     * @return Score
     * @throws VoteInvalidTypeException
     */
    public function fromPersistenceToDomain($score): Score
    {
        $className = array_search($score, self::TRANSFORMATIONS, true);
        if (false === $className) {
            throw new VoteInvalidTypeException($score);
        }

        return $className::get();
    }
}
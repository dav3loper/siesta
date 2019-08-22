<?php
namespace siesta\infrastructure\vote\persistence;

use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\vote\IndividualVote;

class EloquentVoteSerializedTransformer
{
    /**
     * EloquentVoteRecorderTransformer constructor.
     * @param ScoreTransformer $scoreTransformer
     */
    const USER_ID = 'userId';
    const SCORE = 'score';
    /** @var ScoreTransformer */
    private $_scoreTransformer;

    public function __construct(ScoreTransformer $scoreTransformer)
    {
        $this->_scoreTransformer = $scoreTransformer;
    }

    /**
     * @param IndividualVote[] $getIndividualVoteList
     * @return string
     * @throws VoteInvalidTypeException
     */
    public function getSerializedVotes(array $getIndividualVoteList)
    {
        $serialized = [];
        foreach ($getIndividualVoteList as $individualVote) {
            $score = $this->_scoreTransformer->fromDomainToPersistence($individualVote->getScore());
            $serialized[] = [self::USER_ID => $individualVote->getUserId(), self::SCORE => $score];
        }

        return json_encode($serialized);
    }

    /**
     * @param string $data
     * @return IndividualVote[]
     */
    public function getDeserializedVotes($data)
    {
        $individualVoteList = [];
        foreach (json_decode($data, true) as $individualVote) {
            $score = $this->_scoreTransformer->fromPersistenceToDomain($individualVote[self::SCORE]);
            $individualVoteList[] = new IndividualVote($score, $individualVote[self::USER_ID]);
        }

        return $individualVoteList;
    }
}
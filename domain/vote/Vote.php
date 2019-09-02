<?php
namespace siesta\domain\vote;

use siesta\domain\movie\Movie;

class Vote
{

    public const STRONG_SCORE = 2;
    public const WEAK_SCORE = 1;
    public const NO_SCORE = 0;

    /** @var IndividualVote[] */
    private $_individualVoteList;
    /** @var Movie */
    private $_movie;
    /** @var int */
    private $_totalScore;

    /**
     * @return IndividualVote[]
     */
    public function getIndividualVoteList(): array
    {
        return $this->_individualVoteList;
    }

    /**
     * @param IndividualVote[] $scoreList
     */
    public function setIndividualVoteList(array $scoreList): void
    {
        $this->_individualVoteList = $scoreList;
    }

    /**
     * @return int
     */
    public function getMovieId(): int
    {
        return $this->_movie->getId();
    }

    /**
     * @param Movie $movie
     */
    public function setMovie(Movie $movie): void
    {
        $this->_movie = $movie;
    }

    /**
     * @param int $value
     */
    public function setScore(int $value)
    {
        $this->_totalScore = $value;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->_totalScore;
    }
}
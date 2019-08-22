<?php
namespace siesta\domain\vote;

class IndividualVote
{
    /** @var Score */
    private $_score;
    /** @var int */
    private $_userId;

    /**
     * IndividualVote constructor.
     * @param Score $score
     * @param int $userId
     */
    public function __construct(Score $score, int $userId)
    {
        $this->_score = $score;
        $this->_userId = $userId;
    }

    /**
     * @return Score
     */
    public function getScore(): Score
    {
        return $this->_score;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->_userId;
    }

}
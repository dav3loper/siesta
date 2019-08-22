<?php
namespace siesta\application\movie\usecases;

use siesta\domain\exception\vote\VoteInvalidTypeException;

class VoteMovieCommand
{
    /** @var int */
    private $_id;
    /** @var array */
    private $_individualVotes;

    public function __construct()
    {
        $this->_individualVotes = [];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->_id = $id;
    }

    /**
     * @param array $data
     * @throws VoteInvalidTypeException
     */
    public function setIndividualVote(array $data): void
    {

        foreach ($data as $userId => $value) {
            if (preg_match('/user_(?<user_id>\d+)/', $userId, $matches)) {
                $this->_individualVotes[$matches['user_id']] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getIndividualVotes(): array
    {
        return $this->_individualVotes;
    }
}
<?php
namespace siesta\application\movie\usecases;

use siesta\domain\exception\MissingParameterException;
use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\exception\vote\VoteRecordException;
use siesta\domain\movie\Movie;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\infrastructure\VoteRecorder;
use siesta\domain\vote\Vote;

class VoteMovieHandler
{

    /** @var VoteRecorder */
    private $_voteRecorder;

    /**
     * VoteMovieHandler constructor.
     * @param VoteRecorder $voteRecorder
     */
    public function __construct(VoteRecorder $voteRecorder)
    {
        $this->_voteRecorder = $voteRecorder;
    }

    /**
     * @param VoteMovieCommand $command
     * @throws MissingParameterException
     * @throws VoteRecordException
     * @throws VoteInvalidTypeException
     */
    public function execute(VoteMovieCommand $command)
    {
        $this->_checkParams($command);

        $vote = new Vote();
        $movie = new Movie();
        $movie->setId($command->getId());
        $vote->setMovie($movie);
        $vote->setIndividualVoteList($this->_getIndividualVotes($command));
        $this->_voteRecorder->store($vote);
    }

    /**
     * @param VoteMovieCommand $command
     * @throws MissingParameterException
     */
    private function _checkParams(VoteMovieCommand $command)
    {
        if (empty($command->getId())) {
            throw new MissingParameterException('id');
        }
        if (empty($command->getIndividualVotes())) {
            throw new MissingParameterException('votes');
        }
    }

    /**
     * @param VoteMovieCommand $command
     * @return array
     * @throws VoteInvalidTypeException
     */
    private function _getIndividualVotes(VoteMovieCommand $command)
    {
        $individualVoteList = [];
        foreach ($command->getIndividualVotes() as $userId => $individualVote) {
            $score = VoteTransformer::fromValueToDomain($individualVote);
            $individualVoteList[] = new IndividualVote($score, $userId);
        }

        return $individualVoteList;
    }

}
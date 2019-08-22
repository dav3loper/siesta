<?php
namespace siesta\application\movie\usecases;

use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\movie\Movie;
use siesta\domain\vote\infrastructure\VoteProvider;
use siesta\domain\vote\Vote;

class ObtainMovieHandler
{
    /** @var MovieProvider */
    private $_movieProvider;
    /** @var VoteProvider */
    private $_voteProvider;

    /**
     * ObtainMovieHandler constructor.
     * @param MovieProvider $movieProvider
     */
    public function __construct(MovieProvider $movieProvider, VoteProvider $voteProvider)
    {
        $this->_movieProvider = $movieProvider;
        $this->_voteProvider = $voteProvider;
    }

    /**
     * @param ObtainMovieCommand $command
     * @return Movie
     * @throws MovieNotFoundException
     */
    public function execute(ObtainMovieCommand $command): Movie
    {
        $movie = $this->_movieProvider->getMovieById($command->getId());
        $vote = $this->_getVotesFromMovieId($command);
        if ($vote) {
            $movie->setVote($vote);
        }

        return $movie;
    }

    /**
     * @param ObtainMovieCommand $command
     * @return Vote
     */
    protected function _getVotesFromMovieId(ObtainMovieCommand $command)
    {
        try {
            return $this->_voteProvider->getVotesByMovieId($command->getId());
        } catch (VoteNotFoundException $e) {
            return null;
        }
    }
}
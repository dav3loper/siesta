<?php
namespace siesta\application\movie\usecases;

use siesta\application\movie\usecases\response\ObtainMovieResponse;
use siesta\domain\exception\MovieNotForVoteException;
use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\user\infrastructure\UserProvider;
use siesta\domain\user\User;
use siesta\domain\vote\infrastructure\VoteProvider;
use siesta\domain\vote\Vote;

class ObtainMovieHandler
{
    private const NONE = -1;

    /** @var MovieProvider */
    private $_movieProvider;
    /** @var VoteProvider */
    private $_voteProvider;
    /** @var UserProvider */
    private $_userProvider;

    /**
     * ObtainMovieHandler constructor.
     * @param MovieProvider $movieProvider
     * @param VoteProvider $voteProvider
     * @param UserProvider $userProvider
     */
    public function __construct(MovieProvider $movieProvider, VoteProvider $voteProvider, UserProvider $userProvider)
    {
        $this->_movieProvider = $movieProvider;
        $this->_voteProvider = $voteProvider;
        $this->_userProvider = $userProvider;
    }

    /**
     * @param ObtainMovieCommand $command
     * @return ObtainMovieResponse
     * @throws MovieNotFoundException
     * @throws MovieNotForVoteException
     */
    public function execute(ObtainMovieCommand $command): ObtainMovieResponse
    {
        if($command->getId() == self::NONE){
            throw new MovieNotForVoteException();
        }
        $movie = $this->_movieProvider->getMovieById($command->getId());
        $remainingMovies = $this->_getRemainingMovies($command->getUserId(), $movie->getFilmFestivalId());
        $vote = $this->_getVotesFromMovieId($command);
        if ($vote) {
            $movie->setVote($vote);
        }
        $reponse = new ObtainMovieResponse();
        $reponse->setMovie($movie);
        $reponse->setUserList($this->_getUserVoting());
        $reponse->setRemaining($remainingMovies);

        return $reponse;
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

    /**
     * @return User[]
     */
    private function _getUserVoting(): array
    {
        return $this->_userProvider->findAll();
    }

    private function _getRemainingMovies(int $userId, int $getFilmFestivalId): int
    {
        return $this->_movieProvider->getRemainingMoviesFromFilmFestivalAndUser($userId, $getFilmFestivalId);
    }
}

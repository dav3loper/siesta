<?php
namespace siesta\application\home\usecases;

use siesta\application\home\usecases\response\DashboardUserResponse;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\festival\infrastructure\FilmFestivalProvider;
use siesta\domain\vote\infrastructure\VoteProvider;

class DashboardUserHandler
{
    /** @var FilmFestivalProvider */
    private $_festivalProvider;
    /** @var VoteProvider */
    private $_voteProvider;

    /**
     * DashboardUserHandler constructor.
     * @param FilmFestivalProvider $festivalProvider
     * @param VoteProvider $voteProvider
     */
    public function __construct(FilmFestivalProvider $festivalProvider, VoteProvider $voteProvider)
    {
        $this->_festivalProvider = $festivalProvider;
        $this->_voteProvider = $voteProvider;
    }

    /**
     * @return DashboardUserResponse
     */
    public function execute($userId): DashboardUserResponse
    {
        $festivalList = $this->_festivalProvider->getAll();
        $lastFilmVotedPerFestival = [];
        foreach ($festivalList as $festival) {
            try {
                $vote = $this->_voteProvider->getLastVoteByFilmFestivalIdAndUserId($festival->getId(), $userId);
            } catch (VoteNotFoundException $e) {
                continue;
            }
            $lastFilmVotedPerFestival[$festival->getId()] = $vote->getMovieId();
        }

        return new DashboardUserResponse($festivalList, $lastFilmVotedPerFestival);

    }
}
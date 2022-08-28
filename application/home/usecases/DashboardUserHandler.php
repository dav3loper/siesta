<?php
namespace siesta\application\home\usecases;

use siesta\application\home\usecases\response\DashboardUserResponse;
use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\festival\infrastructure\FilmFestivalProvider;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\vote\infrastructure\VoteProvider;

class DashboardUserHandler
{
    private FilmFestivalProvider $_festivalProvider;
    private VoteProvider $_voteProvider;
    private MovieProvider $_movieProvider;

    /**
     * DashboardUserHandler constructor.
     * @param FilmFestivalProvider $festivalProvider
     * @param VoteProvider $voteProvider
     */
    public function __construct(FilmFestivalProvider $festivalProvider, VoteProvider $voteProvider, MovieProvider $movieProvider)
    {
        $this->_festivalProvider = $festivalProvider;
        $this->_voteProvider = $voteProvider;
        $this->_movieProvider = $movieProvider;
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
                $lastFilmVotedPerFestival[$festival->getId()] = $vote->getMovieId();
            } catch (VoteNotFoundException $e) {
                try {
                    $movie = $this->_movieProvider->getFirstMovieByFilmFestival($festival->getId());
                    //TODO: guarrada pa salir del paso
                    $lastFilmVotedPerFestival[$festival->getId()] = $movie->getId()-10;
                }catch (MovieNotFoundException $e){
                    continue;
                }
            }

        }

        return new DashboardUserResponse($festivalList, $lastFilmVotedPerFestival);

    }
}

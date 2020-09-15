<?php


namespace siesta\application\movie\usecases;

use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\movie\Movie;
use siesta\domain\vote\infrastructure\VoteProvider;

class GetNextMovieToVoteHandler
{


    private MovieProvider $movieProvider;
    private VoteProvider $voteProvider;

    public function __construct(MovieProvider $movieProvider, VoteProvider $voteProvider)
    {
        $this->movieProvider = $movieProvider;
        $this->voteProvider = $voteProvider;
    }

    public function execute(GetNextMovieToVoteCommand $command): ?Movie
    {
        $currentMovie = $this->movieProvider->getMovieById($command->getMovieId());

        $movieAfterId = $this->_getNextNonVotedMovie($command, $currentMovie, '>');
        if($movieAfterId){
            return $movieAfterId;
        }
        $movieBeforeId = $this->_getNextNonVotedMovie($command, $currentMovie, '<');
        if($movieBeforeId){
            return $movieBeforeId;
        }
        return null;
    }

    public function _getNextNonVotedMovie(GetNextMovieToVoteCommand $command, Movie $currentMovie, string $operator): ?Movie
    {
        try {
            return $this->movieProvider->getNextNonVotedMovie($command->getMovieId(), $currentMovie->getFilmFestivalId(), $command->getUserId(), $operator);
        } catch (MovieNotFoundException $e) {
            return null;
        }
    }
}

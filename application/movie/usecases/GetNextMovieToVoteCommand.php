<?php


namespace siesta\application\movie\usecases;


class GetNextMovieToVoteCommand
{

    private int $movieId;
    private int $userId;

    public function __construct(int $movieId, int $userId)
    {
        $this->movieId = $movieId;
        $this->userId = $userId;
    }

    public function getMovieId(): int
    {
        return $this->movieId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

}

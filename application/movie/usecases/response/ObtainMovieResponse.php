<?php
namespace siesta\application\movie\usecases\response;

use siesta\domain\movie\Movie;
use siesta\domain\user\User;

class ObtainMovieResponse
{
    /** @var Movie */
    private $_movie;

    /** @var User[] */
    private $_userList;

    /**
     * @return Movie
     */
    public function getMovie()
    {
        return $this->_movie;
    }

    /**
     * @param Movie $movie
     */
    public function setMovie(Movie $movie): void
    {
        $this->_movie = $movie;
    }

    /**
     * @return User[]
     */
    public function getUserList()
    {
        return $this->_userList;
    }

    /**
     * @param User[] $userList
     */
    public function setUserList(array $userList): void
    {
        $this->_userList = $userList;
    }

}
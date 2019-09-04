<?php
namespace siesta\application\home\usecases\response;

use siesta\domain\festival\FilmFestival;

class DashboardUserResponse
{
    /** @var FilmFestival[] */
    private $_filmFestivalList;
    /** @var array */
    private $_lastVotedFilm;

    /**
     * DashboardUserResponse constructor.
     * @param FilmFestival[] $festivalList
     * @param array $lastVotedFilmPerFestival
     */
    public function __construct(array $festivalList, array $lastVotedFilmPerFestival)
    {
        $this->_filmFestivalList = $festivalList;
        $this->_lastVotedFilm = $lastVotedFilmPerFestival;
    }

    /**
     * @return FilmFestival[]
     */
    public function getFilmFestivalList(): array
    {
        return $this->_filmFestivalList;
    }

    /**
     * @return array
     */
    public function getLastVotedFilmPerFestival()
    {
        return $this->_lastVotedFilm;
    }
}
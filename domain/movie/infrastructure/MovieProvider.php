<?php
namespace siesta\domain\movie\infrastructure;


use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\movie\Movie;

/**
 * Interface MovieProvider
 * @package siesta\domain\model\movie
 */
interface MovieProvider
{
    /**
     * @throws MovieNotFoundException
     */
    public function getMovieById(int $id): Movie;

    /**
     * @throws MovieNotFoundException
     */
    public function getMovieByTitle(string $title): Movie;

    /**
     * @throws MovieNotFoundException
     */
    public function getFirstMovieByFilmFestival(int $festivalId): Movie;
}

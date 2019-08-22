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
     * @param int $id
     * @return Movie
     * @throws MovieNotFoundException
     */
    public function getMovieById($id): Movie;
}
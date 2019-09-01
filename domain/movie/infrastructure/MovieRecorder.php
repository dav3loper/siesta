<?php
namespace siesta\domain\movie\infrastructure;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\MovieRecordException;
use siesta\domain\movie\Movie;

/**
 * Interface MovieRecorder
 * @package siesta\domain\model\movie
 */
interface MovieRecorder
{
    /**
     * @param Movie $movie
     * @throws MovieRecordException
     */
    public function store(Movie $movie);

    /**
     * @param Movie $movie
     * @throws ModelNotFoundException
     */
    public function updateMovie(Movie $movie);
}
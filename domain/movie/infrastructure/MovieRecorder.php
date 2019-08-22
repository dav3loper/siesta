<?php
namespace siesta\domain\movie\infrastructure;


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
}
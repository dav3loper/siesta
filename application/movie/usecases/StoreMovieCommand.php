<?php
namespace siesta\application\movie\usecases;

class StoreMovieCommand
{
    /** @var string */
    private $_title;
    /** @var string */
    private $_summary;
    /** @var string */
    private $_poster;
    /** @var int */
    private $_duration;
    /** @var string */
    private $_trailer;

    /**
     * StoreMovieCommand constructor.
     * @param string $title
     * @param string $summary
     * @param string $poster
     * @param int $duration
     * @param string $trailer
     */
    private function __construct($title, $summary, $poster, $duration, $trailer)
    {
        $this->_title = $title;
        $this->_summary = $summary;
        $this->_poster = $poster;
        $this->_duration = $duration;
        $this->_trailer = $trailer;
    }

    /**
     * @param string $rawData
     * @return StoreMovieCommand
     */
    public static function buildFromJsonData(string $rawData): StoreMovieCommand
    {
        $data = json_decode($rawData);

        return new self($data->title, $data->summary, $data->poster, $data->duration, $data->trailer);
    }

    /**
     * @param \siesta\domain\movie\Movie $movie
     * @return StoreMovieCommand
     */
    public static function buildFromMovie(\siesta\domain\movie\Movie $movie): StoreMovieCommand
    {
        return new self($movie->getTitle(), $movie->getSummary(), $movie->getPoster(), $movie->getDuration(), $movie->getTrailerId());
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->_summary;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->_poster;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->_duration;
    }

    /**
     * @return string
     */
    public function getTrailer(): string
    {
        return $this->_trailer;
    }


}
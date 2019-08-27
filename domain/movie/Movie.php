<?php
namespace siesta\domain\movie;

use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\Vote;

class Movie
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
    private $_trailerId;
    /** @var int */
    private $_id;
    /** @var Vote */
    private $_vote;
    /** @var int */
    private $_filmFestivalId;

    /**
     * @param int $movieId
     * @return Movie
     */
    public static function buildFromId($movieId)
    {
        $movie = new self();
        $movie->setId($movieId);

        return $movie;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->_duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration): void
    {
        if (!\is_int($duration)) {
            $this->_duration = 0;
        } else {
            $this->_duration = $duration;
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->_poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster($poster): void
    {
        $this->_poster = $poster;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->_summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary): void
    {
        $this->_summary = $summary;
    }

    /**
     * @return string
     */
    public function getTrailerId(): string
    {
        return $this->_trailerId;
    }

    /**
     * @param string $trailer
     */
    public function setTrailerId($trailer): void
    {
        $this->_trailerId = $trailer;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->_title,
            'poster' => $this->_poster,
            'trailer' => $this->_trailerId,
            'duration' => $this->_duration,
            'summary' => $this->_summary,
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->_id = $id;
    }

    /**
     * @return Vote
     */
    public function getVote(): Vote
    {
        return $this->_vote;
    }

    /**
     * @param Vote $vote
     */
    public function setVote(Vote $vote): void
    {
        $this->_vote = $vote;
    }

    /**
     * @return IndividualVote[]
     */
    public function getIndividualVoteList()
    {
        if ($this->_vote) {
            return $this->_vote->getIndividualVoteList();
        }

        return [];
    }

    /**
     * @param int $getFilmFestivalId
     */
    public function setFilmFestivalId(int $getFilmFestivalId): void
    {
        $this->_filmFestivalId = $getFilmFestivalId;
    }

    /**
     * @return int
     */
    public function getFilmFestivalId(): int
    {
        return $this->_filmFestivalId;
    }
}
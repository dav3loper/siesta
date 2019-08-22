<?php
namespace App\Presentation;

use siesta\domain\movie\Movie;
use siesta\domain\vote\Score;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;

class MovieDecorator
{
    private const DURATION_SUFFIX = 'mins';

    /** @var Movie */
    private $_movie;

    /**
     * MovieDecorator constructor.
     * @param Movie $movie
     */
    public function __construct(Movie $movie)
    {
        $this->_movie = $movie;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return ucwords(strtolower($this->_movie->getTitle()));
    }

    /**
     * @return int
     */
    public function getDuration(): string
    {
        return $this->_movie->getDuration() . ' ' . self::DURATION_SUFFIX;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->_movie->getPoster();
    }

    /**
     * @return string
     */
    public function getTrailer(): string
    {
        return sprintf('https://www.youtube.com/embed/%s?rel=0&amp;showinfo=0', $this->_movie->getTrailerId());
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return preg_replace('/ +/', ' ', $this->_movie->getSummary());
    }

    /**
     * @param int $id
     * @return string
     */
    public function isWeakScore($id): string
    {
        return $this->_isScore($id, WeakScore::get());
    }

    /**
     * @param int $id
     * @param Score $score
     * @return string
     */
    private function _isScore($id, Score $score): string
    {
        foreach ($this->_movie->getIndividualVoteList() as $individualVote) {
            if ($individualVote->getUserId() === $id &&
                $individualVote->getScore() === $score) {
                return 'checked';
            }
        }

        return '';
    }

    /**
     * @param int $id
     * @return string
     */
    public function isStrongScore($id): string
    {
        return $this->_isScore($id, StrongScore::get());
    }

    /**
     * @return string
     */
    public function getPosterHeight()
    {
        if ($this->_isSitgesImage()) {
            return '333px';
        }

        return '700px';
    }

    /**
     * @return bool
     */
    private function _isSitgesImage()
    {
        return false !== strpos($this->_movie->getPoster(), 'sitgesfilmfestival.com');
    }

    /**
     * @return string
     */
    public function getPosterWidth()
    {
        if ($this->_isSitgesImage()) {
            return '500px';
        }

        return '500px';
    }
}
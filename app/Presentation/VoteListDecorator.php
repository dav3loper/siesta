<?php
namespace App\Presentation;

use App\Helpers\UrlGenerator;
use siesta\domain\movie\Movie;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;

class VoteListDecorator
{
    private const RELATIONS = [
        '0' => 'D',
        '1' => 'S',
        '2' => 'U',
        '3' => 'L',
        '4' => 'M',
    ];
    /** @var Movie[] */
    private $_movieList;
    //fixme: sacar relacion de usuarios a env o algo
    /** @var int */
    private $_current;

    /**
     * VoteListDecorator constructor.
     * @param Movie[] $movieList
     */
    public function __construct(array $movieList)
    {
        $this->_movieList = $movieList;
        $this->_current = 0;
    }

    /**
     * @return string
     */
    public function getCurrentMovieLink(): string
    {
        $current = $this->_getCurrentMovie();

        return UrlGenerator::getShowMovie($current->getId());
    }

    /**
     * @return Movie
     */
    private function _getCurrentMovie(): Movie
    {
        return $this->_movieList[$this->_current];
    }

    /**
     * @return string
     */
    public function getCurrentMovieName(): string
    {
        $current = $this->_getCurrentMovie();

        return ucwords(strtolower($current->getTitle()));
    }

    /**
     * @return string
     */
    public function getCurrentVote(): string
    {
        $voteList = $this->_getCurrentMovie()->getIndividualVoteList();
        $stringVotation = '';
        foreach ($voteList as $individualVote) {
            if ($individualVote->getScore() instanceof StrongScore) {
                $initial = self::RELATIONS[$individualVote->getUserId()];
                $stringVotation .= strtoupper($initial);
            }
            if ($individualVote->getScore() instanceof WeakScore) {
                $initial = self::RELATIONS[$individualVote->getUserId()];
                $stringVotation .= strtolower($initial);
            }
        }

        return $stringVotation;
    }

    /**
     * @return int
     */
    public function getTotalSize(): int
    {
        return \count($this->_movieList);
    }

    public function next(): void
    {
        $this->_current++;
    }

    /**
     * @return string
     */
    public function getCurrentMovieColor(): string
    {
        return '';

        $vote = $this->_getCurrentMovie()->getVote();
        $totalScore = $vote->getScore();
        if ($totalScore > 7) {
            return 'green-vote';
        }
        if ($totalScore > 4) {
            return 'yellow-vote';
        }
        if ($totalScore > 1) {
            return 'orange-vote';
        }

        return 'red-vote';
    }
}
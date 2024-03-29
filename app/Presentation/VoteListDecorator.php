<?php
namespace App\Presentation;

use App\Helpers\UrlGenerator;
use siesta\domain\movie\Movie;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;

class VoteListDecorator
{
    private const RELATIONS = [
        '1' => 'S',
        '21' => 'D',
        '11' => 'U',
        '41' => 'L',
        '51' => 'E',
        '31' => 'M',
    ];
    private const VOTES_ORDER = ['D', 'd', 'S', 's', 'U', 'u', 'L', 'l', 'M', 'm', 'E', 'e'];

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

        return ucwords(mb_strtolower($current->getTitle()));
    }

    /**
     * @return string
     */
    public function getCurrentVote(): string
    {
        $voteList = $this->_getCurrentMovie()->getIndividualVoteList();
        $stringVotation = [];
        foreach ($voteList as $individualVote) {
            if ($individualVote->getScore() instanceof StrongScore) {
                $initial = self::RELATIONS[$individualVote->getUserId()];
                $stringVotation[] = strtoupper($initial);
            }
            if ($individualVote->getScore() instanceof WeakScore) {
                $initial = self::RELATIONS[$individualVote->getUserId()];
                $stringVotation[] = strtolower($initial);
            }
        }
        uasort($stringVotation, function ($a, $b) {
            $aOrder = array_search($a, self::VOTES_ORDER, true);
            $bOrder = array_search($b, self::VOTES_ORDER, true);

            return $aOrder > $bOrder;
        });

        return implode('', $stringVotation);
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


    public function getCurrentMovieAlias(): ?string
    {
        $current = $this->_getCurrentMovie();

        $alias = $current->getAlias();
        if($alias) {
            return ' [' . strtolower($alias) .']';
        }
        return '';

    }
}

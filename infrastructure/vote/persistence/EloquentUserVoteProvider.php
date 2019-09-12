<?php
namespace siesta\infrastructure\vote\persistence;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\movie\Movie;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\infrastructure\VoteProvider;
use siesta\domain\vote\Vote;

/**
 * Class EloquentUserVoteProvider
 * @package siesta\infrastructure\vote\persistence
 */
class EloquentUserVoteProvider extends Model implements VoteProvider
{
    private const TABLE_NAME = 'user_vote';
    private const MOVIE_ID = 'movie_id';
    const ID = 'id';

    /** @var ScoreTransformer */
    private $_scoreTransformer;

    /**
     * EloquentUserVoteProvider constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = self::TABLE_NAME;
        parent::__construct($attributes);
        $this->_scoreTransformer = app()->make(ScoreTransformer::class);
    }

    /**
     * @param int $id
     * @return Vote
     * @throws VoteNotFoundException
     */
    public function getVotesByMovieId(int $id): Vote
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentUserVoteProvider $mapping */
            $mappingList = self::where(self::MOVIE_ID, '=', $id)->get();

            return $this->_getVoteFromMapping($mappingList, $id);
        } catch (ModelNotFoundException $e) {
            throw new VoteNotFoundException($e);
        }
    }

    /**
     * @param array|Collection $mappingList
     * @param int $movieId
     * @return Vote
     */
    private function _getVoteFromMapping($mappingList, $movieId): Vote
    {
        $vote = new Vote();
        $vote->setMovie(Movie::buildFromId($movieId));
        $individualVoteList = [];
        foreach ($mappingList as $mapping) {
            $score = $this->_scoreTransformer->fromPersistenceToDomain($mapping->score);
            $individualVoteList[] = new IndividualVote($score, $mapping->user_id);
        }
        $vote->setIndividualVoteList($individualVoteList);

        return $vote;
    }

    /**
     * @return Vote[]
     */
    public function getVotesOfFilmFestivalIdOrderedByScore($filmFestivalId): array
    {

        $mappingList = self::join('movie', 'movie_id', '=', 'movie.id')
            ->where('movie.film_festival_id', '=', $filmFestivalId)
            //TODO :select los campos q quiero!
            ->get();

        $algoList = [];
        foreach ($mappingList as $mapping) {
            $algoList[$mapping->movie_id][] = $mapping;
        }
        $voteList = [];
        foreach ($algoList as $movieId => $algo) {
            $vote = $this->_getVoteFromMapping($algo, $movieId);
            $voteList[] = $vote;
        }
        usort($voteList, $this->_orderByTotalScore());

        return $voteList;
    }

    /**
     * @return \Closure
     */
    protected function _orderByTotalScore(): callable
    {
        return function (Vote $vote1, Vote $vote2) {
            $totalScoreA = 0;
            foreach ($vote1->getIndividualVoteList() as $individualVote) {
                $value = $this->_scoreTransformer->fromDomainToPersistence($individualVote->getScore());
                $vote1->setScore($value);
                $totalScoreA += $value;
            }
            $vote1->setScore($totalScoreA);
            $totalScoreB = 0;
            foreach ($vote2->getIndividualVoteList() as $individualVote) {
                $value = $this->_scoreTransformer->fromDomainToPersistence($individualVote->getScore());
                $totalScoreB += $value;
            }
            $vote2->setScore($totalScoreB);
            if ($totalScoreA === $totalScoreB) {
                return 0;
            }

            return $totalScoreA < $totalScoreB ? 1 : -1;
        };
    }

    /**
     * @param int $filmFestivalId
     * @param int $userId
     * @return Vote
     * @throws VoteNotFoundException
     */
    public function getLastVoteByFilmFestivalIdAndUserId($filmFestivalId, $userId): Vote
    {
        //TODO: se pueden usar relaciones?
        $mapping = self::join('movie', 'movie_id', '=', 'movie.id')
            ->where('user_vote.user_id', '=', $userId)
            ->where('movie.film_festival_id', '=', $filmFestivalId)
            ->latest('user_vote.created_at')
            ->first();
        if ($mapping === null) {
            throw new VoteNotFoundException();
        }

        return $this->_getVoteFromMapping([$mapping], $mapping->id);
    }
}
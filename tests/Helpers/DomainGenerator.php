<?php
namespace Tests\Helpers;

use siesta\domain\movie\Movie;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\Vote;
use siesta\domain\vote\WeakScore;
use siesta\infrastructure\vote\persistence\ScoreTransformer;

/**
 * Class DomainGenerator
 * @package Tests\Helpers
 */
class DomainGenerator
{
    /**
     * @param array $attributes
     * @return Movie
     */
    public static function getMovieFromDBData(array $attributes)
    {

        $movie = new Movie();
        $movie->setTrailerId($attributes['trailer_id']);
        $movie->setTitle($attributes['title']);
        $movie->setPoster($attributes['poster']);
        $movie->setDuration($attributes['duration']);
        $movie->setSummary($attributes['summary']);
        $movie->setId($attributes['id']);

        return $movie;
    }

    /**
     * @return Vote
     */
    public static function givesVote(): Vote
    {
        $vote = new Vote();
        $vote->setIndividualVoteList([new IndividualVote(WeakScore::get(), 1), new IndividualVote(StrongScore::get(), 2)]);
        $vote->setMovie(self::givesMovie());

        return $vote;
    }

    /**
     * @return Movie
     */
    public static function givesMovie()
    {
        $movie = new Movie();
        $movie->setDuration(90);
        $movie->setPoster('poster');
        $movie->setSummary('summary');
        $movie->setTitle('testMovie');
        $movie->setTrailerId('coolTrailer');
        $movie->setId(random_int(1, 1000));

        return $movie;
    }

    public static function getVoteFromDBData($attributes)
    {
        /** @var ScoreTransformer $scoreTransformer */
        $scoreTransformer = app()->get(ScoreTransformer::class);
        $movie = new Movie();
        $movie->setId($attributes['movie_id']);
        $vote = new Vote();
        $vote->setMovie($movie);
        $votesData = json_decode($attributes['votes'], true);
        $individualVoteList = [];
        foreach ($votesData as $individualVote) {
            $scoreTransformed = $scoreTransformer->fromPersistenceToDomain($individualVote['score']);
            $individualVoteList[] = new IndividualVote($scoreTransformed, $individualVote['userId']);
        }
        $vote->setIndividualVoteList($individualVoteList);

        return $vote;
    }
}
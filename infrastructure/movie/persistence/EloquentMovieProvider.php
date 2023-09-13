<?php
namespace siesta\infrastructure\movie\persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\movie\Movie;

class EloquentMovieProvider extends Model implements MovieProvider
{

    private const TABLE_NAME = 'movie';
    private const FILLABLE_FIELDS = ['title', 'poster', 'trailer_id', 'duration', 'summary', 'link', 'comments', 'film_festival_id', 'alias'];
    private const ID = 'id';
    private const TITLE = 'title';
    const FILM_FESTIVAL_ID = 'film_festival_id';

    /** @var EloquentMovieTransformer */
    private $_transformer;


    /**
     * EloquentMovieRecorder constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        parent::__construct($attributes);
        $this->_transformer = new EloquentMovieTransformer();
    }


    /**
     * @param int $id
     * @return Movie
     * @throws MovieNotFoundException
     */
    public function getMovieById($id): Movie
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentMovieProvider $mapping */
            $mapping = self::where(self::ID, '=', $id)->firstOrFail();

            return $this->_getMovieFromMapping($mapping->getAttributes());
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException($e);
        }
    }

    /**
     * @param array $mapping
     * @return Movie
     */
    private function _getMovieFromMapping(array $mapping): Movie
    {
        $fields = self::FILLABLE_FIELDS;
        $fields[] = self::ID;

        return $this->_transformer->fromArrayToMovie($mapping, $fields);
    }

    public function getMovieByTitle(string $title): Movie
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentMovieProvider $mapping */
            $mapping = self::where(self::TITLE, '=', $title)->firstOrFail();

            return $this->_getMovieFromMapping($mapping->getAttributes());
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException($e);
        }
    }

    public function getFirstMovieByFilmFestival(int $festivalId): Movie
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentMovieProvider $mapping */
            $mapping = self::where(self::FILM_FESTIVAL_ID, '=', $festivalId)->sortBy('id')->firstOrFail();

            return $this->_getMovieFromMapping($mapping->getAttributes());
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException($e);
        }
    }

    public function getNextNonVotedMovie(int $getMovieId, int $getFilmFestivalId, int $userId, string $operator): Movie
    {
        return 1;
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentMovieProvider $mapping */
            //TODO: sacar a raws
            $mappingList = DB::select('SELECT *
                                      FROM movie m
                                      where m.film_festival_id = '.$getFilmFestivalId.' and m.id '.$operator.' '.$getMovieId.' and m.id NOT IN (
	                                  select v.movie_id from user_vote v
                                      where v.user_id = '.$userId.')
                                      order by m.id asc
                                      limit 1;');
            $mapping = current($mappingList);
            if(empty($mapping)){
                throw new MovieNotFoundException();
            }

            return $this->_getMovieFromMapping((array)$mapping);
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException($e);
        }

    }

    public function getRemainingMoviesFromFilmFestivalAndUser(int $userId, int $filmFestivalId): int
    {
        return 1;
        try {
            //TODO: sacar a raws
            $count = DB::select('SELECT count(id) as movieRemaining
                                      FROM movie m
                                      where m.film_festival_id = '.$filmFestivalId.' and m.id NOT IN (
	                                  select v.movie_id from user_vote v
                                      where v.user_id = '.$userId.')');
            return current($count)->movieRemaining;
        } catch (ModelNotFoundException $e) {
            return 0;
        }
    }
}

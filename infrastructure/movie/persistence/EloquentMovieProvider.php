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
            $mapping = self::where(self::FILM_FESTIVAL_ID, '=', $festivalId)->orderBy('id', 'asc')->firstOrFail();

            return $this->_getMovieFromMapping($mapping->getAttributes());
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException($e);
        }
    }

    public function getNextNonVotedMovie(int $getMovieId, int $getFilmFestivalId, int $userId, string $operator): Movie
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentMovieProvider $mapping */


            $mapping = DB::table('movie')
                ->where(self::FILM_FESTIVAL_ID, '=', $getFilmFestivalId)
                ->where('id', $operator, $getMovieId)
                ->whereNotIn('id', function($query) use ($userId){
                    $query->select('movie_id')->from('user_vote')->where('user_id', '=', $userId)
                        ->orderBy('id', 'asc');
                })
                ->first();
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
        try {
            $remaining = DB::table('movie')
                ->where('film_festival_id', '=', $filmFestivalId)
                ->whereNotIn('id', function($query) use ($userId){
                    $query->select('movie_id')->from('user_vote')->where('user_id', '=', $userId);
                })
                ->count();
            return $remaining;
        } catch (ModelNotFoundException $e) {
            return 0;
        }
    }
}

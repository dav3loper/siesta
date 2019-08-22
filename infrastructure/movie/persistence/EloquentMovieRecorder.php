<?php
namespace siesta\infrastructure\movie\persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\MovieRecordException;
use siesta\domain\movie\infrastructure\MovieRecorder;
use siesta\domain\movie\Movie;

/**
 * Class EloquentMovieRecorder
 * @package siesta\infrastructure\movie
 */
class EloquentMovieRecorder extends Model implements MovieRecorder
{
    private const TABLE_NAME = 'movie';
    private const FILLABLE_FIELDS = [self::TITLE, 'poster', 'trailer_id', 'duration', 'summary'];
    private const TITLE = 'title';

    /**
     * EloquentMovieRecorder constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        parent::__construct($attributes);
    }

    /**
     * @param Movie $movie
     * @throws MovieRecordException
     */
    public function store(Movie $movie): void
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            self::where(self::TITLE, '=', $movie->getTitle())->firstOrFail();
        } catch (ModelNotFoundException $e) {
            /** @noinspection PhpUndefinedMethodInspection */
            self::create($this->_getFillableFields($movie));
        } catch (\Exception $e) {
            throw new MovieRecordException($e);
        }
    }

    /**
     * @param Movie $movie
     * @return array
     */
    private function _getFillableFields(Movie $movie): array
    {
        return array_combine($this->fillable, [
            $movie->getTitle(),
            $movie->getPoster(),
            $movie->getTrailerId(),
            $movie->getDuration(),
            $movie->getSummary()

        ]);
    }
}
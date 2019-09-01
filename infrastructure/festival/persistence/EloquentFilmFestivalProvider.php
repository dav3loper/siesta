<?php
namespace siesta\infrastructure\festival\persistence;

use Illuminate\Database\Eloquent\Model;
use siesta\domain\exception\festival\FilmFestivalNotFoundException;
use siesta\domain\festival\FilmFestival;
use siesta\domain\festival\infrastructure\FilmFestivalProvider;

class EloquentFilmFestivalProvider extends Model implements FilmFestivalProvider
{

    private const TABLE_NAME = 'film_festival';
    private const FILLABLE_FIELDS = ['id', 'name', 'edition', 'stars_at', 'ends_at'];

    /**
     * EloquentFilmFestivalProvider constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        parent::__construct($attributes);
    }

    /**
     * @return FilmFestival[]
     * @throws FilmFestivalNotFoundException
     */
    public function getAll(): array
    {
        try {

            $mappingList = self::all();

            return $this->_fromModelToDomain($mappingList);
        } catch (\Exception $e) {
            throw new FilmFestivalNotFoundException($e);
        }
    }

    /**
     * @param Model[] $mappingList
     * @return FilmFestival[]
     */
    private function _fromModelToDomain($mappingList): array
    {
        $filmFestivalList = [];
        foreach ($mappingList as $mapping) {
            $filmFestival = new FilmFestival();
            $filmFestival->setName($mapping->name);
            $filmFestival->setEdition($mapping->edition);
            $filmFestival->setStartsAt(new \DateTime($mapping->starts_at));
            $filmFestival->setEndsAt(new \DateTime($mapping->ends_at));
            $filmFestivalList[] = $filmFestival;
        }

        return $filmFestivalList;
    }
}
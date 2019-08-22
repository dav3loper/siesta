<?php
namespace siesta\infrastructure\movie\persistence;

use siesta\domain\movie\Movie;

class EloquentMovieTransformer
{
    /**
     * @param array $mapping
     * @param array $attributesToFill
     * @return Movie
     */
    public function fromArrayToMovie(array $mapping, array $attributesToFill): Movie
    {
        $movie = new Movie();
        foreach ($attributesToFill as $attribute) {
            $method = $this->_getSetterForMovie($attribute);
            if ($this->_isValidSetter($movie, $method)) {
                $movie->$method($mapping[$attribute]);
            }
        }

        return $movie;
    }

    /**
     * @param $attribute
     * @return string
     */
    private function _getSetterForMovie($attribute): string
    {
        return 'set' . str_replace('_', '', ucwords($attribute, '_'));
    }

    /**
     * @param $movie
     * @param $method
     * @return bool
     */
    private function _isValidSetter($movie, $method): bool
    {
        return \is_callable([$movie, $method]);
    }
}
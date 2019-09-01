<?php
namespace siesta\domain\festival\infrastructure;

use siesta\domain\festival\FilmFestival;

interface FilmFestivalProvider
{
    /**
     * @return FilmFestival[]
     */
    public function getAll(): array;
}
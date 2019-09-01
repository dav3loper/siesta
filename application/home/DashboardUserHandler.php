<?php
namespace siesta\application\home;

use siesta\domain\festival\FilmFestival;
use siesta\domain\festival\infrastructure\FilmFestivalProvider;

class DashboardUserHandler
{
    /**
     * @var FilmFestivalProvider
     */
    private $_festivalProvider;

    /**
     * DashboardUserHandler constructor.
     * @param FilmFestivalProvider $festivalProvider
     */
    public function __construct(FilmFestivalProvider $festivalProvider)
    {
        $this->_festivalProvider = $festivalProvider;
    }

    /**
     * @return FilmFestival[]
     */
    public function execute(): array
    {
        return $this->_festivalProvider->getAll();
    }
}
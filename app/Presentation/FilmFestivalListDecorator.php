<?php
namespace App\Presentation;

use App\Helpers\UrlGenerator;
use siesta\application\home\usecases\response\DashboardUserResponse;
use siesta\domain\festival\FilmFestival;

class FilmFestivalListDecorator implements \Iterator
{


    private const MONTH_TRANSLATIONS = [
        'Ene' => 'Enero',
        'Feb' => 'Febrero',
        'Mar' => 'Marzo',
        'Apr' => 'Abril',
        'May' => 'Mayo',
        'Jun' => 'Junio',
        'Jul' => 'Julio',
        'Aug' => 'Agosto',
        'Sep' => 'Septiembre',
        'Oct' => 'Octubre',
        'Nov' => 'Noviembre',
        'Dec' => 'Diciembre',
    ];

    /** @var FilmFestival[] */
    private $_filmFestivalList;
    /** @var int */
    private $_current;
    /** @var array */
    private $_lastVotedFilmPerFestival;

    /**
     * FilmFestivalListDecorator constructor.
     * @param DashboardUserResponse $dashBoardResponse
     */
    public function __construct($dashBoardResponse)
    {
        $this->_filmFestivalList = $dashBoardResponse->getFilmFestivalList();
        $this->_lastVotedFilmPerFestival = $dashBoardResponse->getLastVotedFilmPerFestival();
        $this->_current = 0;
    }


    public function getNextMovieToVote()
    {
        $lastVotedFilm = 1;
        if (array_key_exists($this->_current, $this->_lastVotedFilmPerFestival)) {
            $lastVotedFilm = $this->_lastVotedFilmPerFestival[$this->_current] + 1;
        }

        return UrlGenerator::getShowMovie($lastVotedFilm);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->_current++;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->_current;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->_current < \count($this->_filmFestivalList);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->_current = 0;
    }

    /**
     * @return string
     */
    public function getCurrentDuration(): string
    {
        /** @var FilmFestival $current */
        $current = $this->current();
        $dayStartsAt = $current->getStartsAt()->format('d');
        $monthStartsAt = self::MONTH_TRANSLATIONS[$current->getStartsAt()->format('M')];
        $dayEndsAt = $current->getEndsAt()->format('d');
        $monthEndsAt = self::MONTH_TRANSLATIONS[$current->getEndsAt()->format('M')];

        $startingDate = $dayStartsAt;
        $startingDate .= $monthStartsAt === $monthEndsAt ? '' : ' de ' . $monthStartsAt;

        return sprintf('Del %s al %s',
            $startingDate,
            $dayEndsAt . ' de ' . $monthEndsAt);
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->_filmFestivalList[$this->_current];
    }
}
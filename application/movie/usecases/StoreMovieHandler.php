<?php
namespace siesta\application\movie\usecases;

use siesta\application\exception\WrongInputException;
use siesta\domain\exception\MovieRecordException;
use siesta\domain\movie\infrastructure\MovieRecorder;
use siesta\domain\movie\Movie;

/**
 * Class StoreMovieUseCase
 * @package App\movie\usecases
 */
class StoreMovieHandler
{
    /** @var MovieRecorder */
    private $_recorder;

    /**
     * StoreMovieUseCase constructor.
     * @param MovieRecorder $recorder
     */
    public function __construct(
        MovieRecorder $recorder
    ) {
        $this->_recorder = $recorder;
    }

    /**
     * @param StoreMovieCommand $command
     * @throws WrongInputException
     * @throws MovieRecordException
     */
    public function execute(StoreMovieCommand $command): void
    {

        $this->_checkCommand($command);

        $movie = $this->_getMovieFromInput($command);

        $this->_recorder->store($movie);
    }

    /**
     * @param StoreMovieCommand $command
     * @throws WrongInputException
     */
    private function _checkCommand(StoreMovieCommand $command): void
    {
        try {
            $errors = [];
            if (!\is_int($command->getDuration())) {
                $errors[] = "Incorrect duration:{$command->getDuration()}";
            }
            if (empty($command->getSummary())) {
                $errors[] = "Empty summary:{$command->getSummary()}";
            }
            if (empty($command->getPoster())) {
                $errors[] = "Empty poster:{$command->getPoster()}";
            }
            if (empty($command->getTitle())) {
                $errors[] = "Empty title:{$command->getTitle()}";
            }
            if (empty($command->getFilmFestivalId())) {
                $errors[] = "Empty filmFestivalId:{$command->getFilmFestivalId()}";
            }

            if (!empty($errors)) {
                throw new WrongInputException(json_encode($errors));
            }
        } catch (\TypeError $e) {
            throw new WrongInputException('', 0, $e);
        }
    }

    /**
     * @param StoreMovieCommand $command
     * @return Movie
     */
    private function _getMovieFromInput(StoreMovieCommand $command): Movie
    {
        $movie = new Movie();
        $movie->setTitle($command->getTitle());
        $movie->setSummary($command->getSummary());
        $movie->setPoster($command->getPoster());
        $movie->setDuration($command->getDuration());
        $movie->setTrailerId($command->getTrailer());
        $movie->setFilmFestivalId($command->getFilmFestivalId());

        return $movie;
    }

}
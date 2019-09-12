<?php
namespace siesta\application\movie\usecases;

use siesta\domain\exception\MissingParameterException;
use siesta\domain\movie\infrastructure\MovieRecorder;
use siesta\domain\movie\Movie;

class UpdateTrailerHandler
{

    /** @var MovieRecorder */
    private $_movieRecorder;

    /**
     * UpdateTrailerHandler constructor.
     * @param MovieRecorder $movieRecorder
     */
    public function __construct(MovieRecorder $movieRecorder)
    {
        $this->_movieRecorder = $movieRecorder;
    }

    /**
     * @param UpdateTrailerCommand $command
     * @throws MissingParameterException
     */
    public function execute(UpdateTrailerCommand $command)
    {
        $this->_checkParams($command);

        $this->_updateTrailerId($command);
    }

    /**
     * @param UpdateTrailerCommand $command
     * @throws MissingParameterException
     */
    private function _checkParams(UpdateTrailerCommand $command)
    {
        if (empty($command->getId())) {
            throw new MissingParameterException('id');
        }

        if (empty($command->getTrailerId())) {
            throw new MissingParameterException('trailerId');
        }
    }

    /**
     * @param UpdateTrailerCommand $command
     */
    private function _updateTrailerId(UpdateTrailerCommand $command): void
    {
        $movie = new Movie();
        $movie->setId($command->getId());
        $movie->setTrailerId($command->getTrailerId());
        $this->_movieRecorder->updateMovie($movie);
    }
}
<?php


namespace siesta\application\movie\usecases;


use siesta\domain\exception\MissingParameterException;
use siesta\domain\movie\infrastructure\MovieRecorder;
use siesta\domain\movie\Movie;

class UpdateAliasHandler
{

    private MovieRecorder $_movieRecorder;

    public function __construct(MovieRecorder $movieRecorder)
    {
        $this->_movieRecorder = $movieRecorder;
    }

    /**
     * @throws MissingParameterException
     */
    public function execute(UpdateAliasCommand $command)
    {
        $this->_checkParams($command);

        $this->_updateAlias($command);
    }

    /**
     * @throws MissingParameterException
     */
    private function _checkParams(UpdateAliasCommand $command)
    {
        if (empty($command->getId())) {
            throw new MissingParameterException('id');
        }

        if (empty($command->getAlias())) {
            throw new MissingParameterException('alias');
        }
    }

    private function _updateAlias(UpdateAliasCommand $command): void
    {
        $movie = new Movie();
        $movie->setId($command->getId());
        $movie->setAlias($command->getAlias());
        $this->_movieRecorder->updateMovie($movie);
    }
}

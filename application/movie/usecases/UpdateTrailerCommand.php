<?php
namespace siesta\application\movie\usecases;

class UpdateTrailerCommand
{
    /** @var int */
    private $_id;
    /** @var string */
    private $_trailerId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getTrailerId(): string
    {
        return $this->_trailerId;
    }

    /**
     * @param int $_trailerId
     */
    public function setTrailerId($_trailerId)
    {
        $this->_trailerId = $_trailerId;
    }
}
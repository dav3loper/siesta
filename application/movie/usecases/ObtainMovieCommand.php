<?php
namespace siesta\application\movie\usecases;

class ObtainMovieCommand
{
    /** @var int */
    private $_id;

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
    public function setId(int $id): void
    {
        $this->_id = $id;
    }
}
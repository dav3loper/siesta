<?php
namespace siesta\application\movie\usecases;

class ObtainMovieCommand
{
    /** @var int */
    private $_id;
    /** @var int */
    private $userId;

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

    public function setUserId($id)
    {
        $this->userId = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}

<?php


namespace siesta\application\movie\usecases;


class UpdateAliasCommand
{

    /** @var int */
    private $_id;
    /** @var string */
    private $_alias;

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
    public function getAlias(): string
    {
        return $this->_alias;
    }

    /**
     * @param int $_alias
     */
    public function setAlias($_alias)
    {
        $this->_alias = $_alias;
    }
}

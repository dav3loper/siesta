<?php
namespace siesta\domain\festival;

class FilmFestival
{
    /** @var string */
    private $_name;
    /** @var string */
    private $_edition;
    /** @var \DateTime */
    private $_startsAt;
    /** @var \DateTime */
    private $_endsAt;
    /** @var int */
    private $_id;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getEdition(): string
    {
        return $this->_edition;
    }

    /**
     * @param string $edition
     */
    public function setEdition(string $edition): void
    {
        $this->_edition = $edition;
    }

    /**
     * @return \DateTime
     */
    public function getStartsAt(): \DateTime
    {
        return $this->_startsAt;
    }

    /**
     * @param \DateTime $startsAt
     */
    public function setStartsAt(\DateTime $startsAt): void
    {
        $this->_startsAt = $startsAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt(): \DateTime
    {
        return $this->_endsAt;
    }

    /**
     * @param \DateTime $endsAt
     */
    public function setEndsAt(\DateTime $endsAt): void
    {
        $this->_endsAt = $endsAt;
    }

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
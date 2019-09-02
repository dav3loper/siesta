<?php
namespace siesta\domain\user;

class User
{
    /** @var string */
    private $email;
    /** @var string */
    private $id;
    /** @var string */
    private $_name;

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public static function build()
    {
        return new self;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
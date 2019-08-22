<?php
namespace siesta\domain\exception;

use Throwable;

/**
 * Class InvalidTypeException
 * @package siesta\domain\exception\vote
 */
abstract class InvalidTypeException extends \Exception
{
    private const MESSAGE = '%s invalid type exception: %s';

    /**
     * InvalidTypeException constructor.
     * @param string $className
     * @param Throwable|null $previous
     */
    public function __construct(string $className, Throwable $previous = null)
    {
        $customMessage = sprintf(self::MESSAGE, $this->_getType(), $className);
        parent::__construct($customMessage, 0, $previous);
    }

    /**
     * @return string
     */
    abstract protected function _getType();

}
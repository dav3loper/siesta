<?php
namespace siesta\domain\exception;

use Throwable;

/**
 * Class RecordException
 * @package siesta\domain\exception
 */
abstract class RecordException extends \Exception
{
    private const MESSAGE = 'Error recording %s data: %s';

    /**
     * WrongInputException constructor.
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        $message = $previous ? $previous->getMessage() : '';
        $message = sprintf(self::MESSAGE, $this->_getDataType(), $message);
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    abstract protected function _getDataType(): string;
}
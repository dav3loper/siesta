<?php
namespace siesta\domain\exception;

use Throwable;

abstract class DataNotFoundException extends \Exception
{
    const MESSAGE = '%s not found exception: %s';

    /**
     * DataNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        $message = $previous ? $previous->getMessage() : '';
        $customMessage = sprintf(self::MESSAGE, $this->_getDataType(), $message);
        parent::__construct($customMessage, 0, $previous);
    }

    /**
     * @return string
     */
    abstract protected function _getDataType(): string;
}
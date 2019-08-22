<?php
namespace siesta\domain\exception;

use Throwable;

/**
 * Class MissingParameterException
 * @package siesta\domain\exception
 */
class MissingParameterException extends \Exception
{
    const MESSAGE = 'Missing parameter: %s';

    /**
     * MissingParameterException constructor.
     * @param string $argument
     * @param Throwable|null $previous
     */
    public function __construct(string $argument, Throwable $previous = null)
    {
        $message = sprintf(self::MESSAGE, $argument);
        parent::__construct($message, 0, $previous);
    }
}
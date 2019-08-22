<?php
namespace siesta\application\exception;

use Throwable;

/**
 * Class WrongInputException
 * @package siesta\application\exception
 */
class WrongInputException extends ApplicationSiestaException
{
    private const MESSAGE = 'Wrong input parameters: %s';

    /**
     * WrongInputException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::MESSAGE, $message);
        parent::__construct($message, $code, $previous);
    }
}
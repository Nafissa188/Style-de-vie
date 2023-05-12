<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * ApiHttpException.
 */
class ApiHttpException extends HttpException
{
    /**
     * Constructor.
     *
     * @param int $code The internal exception code
     * @param string $message The internal exception message
     */
    public function __construct($code = 1, $message = null)
    {
        parent::__construct(200, $message, null, array(), $code);
    }
}

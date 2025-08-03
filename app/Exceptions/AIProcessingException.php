<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Exception;

class AIProcessingException extends Exception
{
    use ApiResponses;
    /**
     * Create a new AIProcessingException instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = 'خطأ أثناء معالجة البيانات بواسطة الذكاء الاصطناعي.', int $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request)
    {
        return $this->error($this->getMessage(), $this->getCode() ?: 500);
    }
}

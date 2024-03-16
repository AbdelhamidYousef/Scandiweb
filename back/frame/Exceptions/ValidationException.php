<?php

namespace Frame\Exceptions;

use Exception;

class ValidationException extends Exception
{
    /**
     * The validation errors.
     * 
     * @var array
     */
    private array $errors;

    /**
     * Validation message.
     * 
     * @var string
     */
    protected $message = 'The given data is invalid.';

    /**
     * Create a new exception instance.
     * 
     * @param  array $errors
     * @param  string $message
     * @param  int $code
     * @param  \Exception $previous
     * @return void
     */
    public function __construct(array $errors, string $message = '', int $code = 422, Exception $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message ?: $this->message, $code, $previous);
    }

    /**
     * Get the validation errors.
     * 
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }
}

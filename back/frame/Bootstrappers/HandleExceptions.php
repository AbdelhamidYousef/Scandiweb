<?php

namespace Frame\Bootstrappers;

use ErrorException;
use Frame\Application;
use Frame\Exceptions\HttpException;
use Frame\Exceptions\ValidationException;
use Frame\Http\JsonResponse;
use Throwable;

class HandleExceptions
{
    /**
     * Set the error and exception handlers.
     * 
     * @param  Application $app
     * @return void
     */
    public function boot(Application $app): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Handle errors that occur during execution.
     * 
     * @param  int    $level
     * @param  string $message
     * @param  string $file
     * @param  int    $line
     * @return void
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file, $line): void
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Handle an uncaught exception.
     * 
     * @param  \Throwable $e
     */
    public function handleException(Throwable $e)
    {
        return match (true) {
            $e instanceof HttpException => $this->sendJson($e->getMessage(), $e->getCode()),
            $e instanceof ValidationException => $this->sendValidationResponse($e),
            default => $this->sendJson("Server Error [{$e->getMessage()}]", 500)
        };
    }

    /**
     * Send a validation response.
     * 
     * @param ValidationException $e
     * @return \Frame\Http\JsonResponse
     */
    private function sendValidationResponse(ValidationException $e): JsonResponse
    {
        return $this->sendJson(
            [
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ],
            $e->getCode()
        );
    }

    /**
     * Send a JSON response.
     * 
     * @param string $message
     * @param int    $statusCode
     * @return \Frame\Http\JsonResponse
     */
    private function sendJson(string|array $message, int $statusCode): JsonResponse
    {
        $content = is_array($message) ? $message : ['message' => $message];

        return (new JsonResponse($content, $statusCode))
            ->send();
    }
}

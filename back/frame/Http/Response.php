<?php

namespace Frame\Http;

class Response
{
    /**
     * Response data.
     * 
     * @var mixed
     */
    protected mixed $data;

    /**
     * Status code.
     * 
     * @var int
     */
    protected int $statusCode;

    /**
     * Status text.
     * 
     * @var string
     */
    protected string $statusText;

    /**
     * HTTP Status Codes Description.
     *
     * @var array
     */
    protected static array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        404 => 'Not Found',
        405 => 'Method Not Allowed',

        500 => 'Internal Server Error',
    ];

    /**
     * HTTP version.
     *
     * @var string
     */
    protected string $version;

    /**
     * Response headers.
     * 
     * @var array
     */
    protected array $headers;

    /**
     * Create a new response.
     * 
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     * 
     * @return void
     */
    public function __construct(mixed $data = '', int $statusCode = 200, array $headers = [])
    {
        $this->data = $data;

        $this->statusCode = $statusCode;
        $this->statusText = static::$statusTexts[$statusCode] ?? 'Unknown Status';

        $this->headers = $headers;

        $this->version = $_SERVER["SERVER_PROTOCOL"];
    }

    /**
     * Send HTTP headers and content.
     *
     * @return $this
     */
    public function send(): static
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    /**
     * Send HTTP headers.
     *
     * @return $this
     */
    public function sendHeaders(): static
    {
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->headers() as $name => $value) {
            header($name . ': ' . $value);
        }

        header("HTTP/{$this->version} {$this->statusCode} {$this->statusText}");
        header('Access-Control-Allow-Origin: *');

        return $this;
    }

    /**
     * Send response content as JSON.
     *
     * @return $this
     */
    public function sendContent(): static
    {
        echo $this->data;
        return $this;
    }

    /**
     * Get response headers.
     * 
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Set a new header on the response.
     * 
     * @return $this
     */
    public function setHeader(string $key, string $value): static
    {
        $this->headers[$key] = $value;
        return $this;
    }
}

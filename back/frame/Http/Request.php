<?php

namespace Frame\Http;

class Request
{
    /**
     * The request's HTTP method.
     *
     * @var string
     */
    private string $method;

    /**
     * Request's path (not including base path).
     *
     * @var string
     */
    private string $path;

    /**
     * Request's headers.
     *
     * @var array
     */
    private array $headers;

    /**
     * Request body parameters ($_POST or JSON body).
     *
     * @var array
     */
    private array $inputs;

    /**
     * Create a new request instance.
     * 
     * @param  string $method
     * @param  string $path
     * @param  array $headers
     * @param  array $inputs
     * @return void
     */
    public function __construct(string $method = '', string $path = '', array $headers = [], array $inputs = [])
    {
        $this->method = $method ?: $_SERVER['REQUEST_METHOD'];

        $this->path = $path ?: $this->resolvePath();

        $this->headers = $headers ?: $this->resolveHeaders();

        $this->inputs = $inputs ?: $this->resolveInputs();
    }

    /**
     * Resolve the request's path.
     * 
     * @return string
     */
    private function resolvePath(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        return $this->normalizePath(substr($requestUri, strlen($basePath)));
    }

    /**
     * Resolve the request's input data.
     * 
     * @return array
     */
    private function resolveInputs(): array
    {
        return $_POST ?: (array) json_decode(file_get_contents('php://input'));
    }

    /**
     * Resolve the request's headers.
     * 
     * @return array 
     */
    private function resolveHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            }

            if (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'])) {
                $headers[$key] = $value;
            }
        }

        return $this->normalizeHeaders($headers);
    }

    /**
     * Normalize headers array.
     * 
     * @param  array $headers
     * @return array
     */
    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];

        foreach ($headers as $key => $value) {
            $header = $this->normalizeHeader($key);
            $normalized[$header] = $value;
        }

        return $normalized;
    }

    /**
     * Normalize a header name.
     * 
     * @param  string $header
     * @return string
     */
    private function normalizeHeader(string $header): string
    {
        return strtolower(str_replace('_', '-', $header));
    }

    /**
     * Get the request HTTP method.
     * 
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the request's URI path.
     * 
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get the request's headers array.
     * 
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Get a specific header.
     * 
     * @return string
     */
    public function header(string $header): string
    {
        return $this->headers()[$this->normalizeHeader($header)] ?? '';
    }

    /**
     * Checks if the request has a specific header.
     *
     * @param string $header
     * @return bool
     */
    public function hasHeader(string $header): bool
    {
        return boolval($this->header($header));
    }

    /**
     * Get the request body parameters.
     * 
     * @param string $key
     * @return mixed
     */
    public function inputs(string $key = null): mixed
    {
        return $key ? $this->inputs[$key] : $this->inputs;
    }

    /**
     * Normalize the path by adding a leading slash if not present.
     * 
     * @param string $path
     * @return string
     */
    private function normalizePath(string $path): string
    {
        $path = strtolower($path);
        return $path === '/' ? $path : '/' . trim($path, '/');
    }
}

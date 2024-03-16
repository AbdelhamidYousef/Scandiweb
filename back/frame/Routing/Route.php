<?php

namespace Frame\Routing;

use Frame\Application;
use Frame\Container\Container;
use Frame\Http\Request;

class Route
{
    /**
     * The application instance.
     *
     * @var \Frame\Application
     */
    private Application $app;

    /**
     * The HTTP method the route responds to.
     *
     * @var string
     */
    private string $method;

    /**
     * The path the route responds to.
     *
     * @var string
     */
    private string $path;

    /**
     * The route action array.
     *
     * @var array
     */
    private array $action;

    /**
     * Create a new Route instance.
     *
     * @param Application $app
     * @param string $method
     * @param string $path
     * @param array  $action
     * @return void
     */
    public function __construct(Application $app, string $method, string $path, array $action = [])
    {
        $this->app = $app;

        $this->method = strtoupper($method);
        $this->path = $this->normalizePath($path);
        $this->action = $action;
    }

    /**
     * Run the route action and return the response.
     *
     * @param   \Frame\Http\Request $request
     * @return  mixed
     */
    public function run(Request $request): mixed
    {
        [$controller, $method] = $this->action;

        return $this->app->resolve($controller)->$method($request);
    }

    /**
     * Get the route HTTP method.
     * 
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the route's path.
     * 
     * @return string
     */
    public function path(): string
    {
        return $this->path;
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

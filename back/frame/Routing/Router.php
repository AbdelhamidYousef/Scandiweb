<?php

namespace Frame\Routing;

use Frame\Application;
use Frame\Http\Request;
use Frame\Http\Response;
use Frame\Http\JsonResponse;
use Frame\Exceptions\HttpException;

class Router
{
    /**
     * Application instance.
     * 
     * @var \Frame\Application
     */
    private Application $app;

    /**
     * Router instance.
     * 
     * @var \Frame\Routing\Router
     */
    private static $instance;

    /**
     * An array of the routes keyed by method & path.
     *
     * @var array<string, array<string, \Frame\Routing\Route>> $routes
     */
    private array $routes = [];

    /**
     * Create a new router instance.
     * 
     * @param \Frame\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->setInstance($this);
    }

    /**
     * Store the router instance.
     * 
     * @param \Frame\Routing\Router $instance
     * @return $this
     */
    private function setInstance($instance): static
    {
        static::$instance = $instance;
        return $this;
    }

    /**
     * Get the router instance.
     * 
     * @return static
     */
    public static function instance(): static
    {
        return static::$instance ?? Application::instance()->resolve(Router::class);
    }

    /**
     * Register a new GET route.
     *
     * @param string $path
     * @param array $action
     * @return $this
     */
    public static function get($path, $action): static
    {
        return static::instance()->addRoute('GET', $path, $action);
    }

    /**
     * Register a new POST route.
     *
     * @param string $path
     * @param array $action
     * @return $this
     */
    public static function post($path, $action): static
    {
        return static::instance()->addRoute('POST', $path, $action);
    }

    /**
     * Register a new POST route.
     *
     * @param string $path
     * @param array $action
     * @return $this
     */
    public static function delete($path, $action): static
    {
        return static::instance()->addRoute('DELETE', $path, $action);
    }

    /**
     * Register a new route.
     *
     * @param string $method
     * @param string $path
     * @param array $action
     * @return $this
     */
    private function addRoute(string $method, string $path, array $action): static
    {
        $route = new Route($this->app, $method, $path, $action);

        $this->routes[$route->method()][$route->path()] = $route;

        return $this;
    }

    /**
     * Proccess the request.
     *
     * @param  \Frame\Http\Request $request
     * @return \Frame\Http\Response
     */
    public function dispatch(Request $request): Response
    {
        $route = $this->match($request);

        $response = $this->runThroughPipeline($request, $route);

        return $response->send();
    }

    /**
     * Match the request with a route.
     *
     * @param  \Frame\Http\Request  $request
     * @return \Frame\Routing\Route
     * 
     * @throws \Frame\Exceptions\HttpException
     */
    private function match(Request $request): Route
    {
        $requestPathPattern = "{^" . $request->path() . "$}i";

        if (array_key_exists($request->method(), $this->routes)) {
            $routes = array_values($this->routes[$request->method()]);

            foreach ($routes as $route) {
                if (preg_match($requestPathPattern, $route->path())) {
                    return $route;
                }
            }
        }

        if ($this->isPreflightRequest($request)) {
            return new Route($this->app, 'OPTIONS', $request->path(), []);
        }

        throw new HttpException(
            "Route not found for [{$request->method()}] {$request->path()}.",
            404
        );
    }

    /**
     * Run the request through the pipeline.
     * 
     * @param  \Frame\Http\Request  $request
     * @param  \Frame\Routing\Route $route
     * 
     * @return \Frame\Http\JsonResponse
     */
    private function runThroughPipeline(Request $request, Route $route)
    {
        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->middleware())
            ->via('handle')
            ->then(fn ($request) => $this->jsonResponse($route->run($request)));
    }

    /**
     * Prepare the response.
     * 
     * @param  mixed $response
     * @return \Frame\Http\JsonResponse
     */
    private function jsonResponse(mixed $response): JsonResponse
    {
        return new JsonResponse($response);
    }

    /**
     * Check if the request is a preflight request.
     * 
     * @param  \Frame\Http\Request $request
     * @return bool
     */
    private function isPreflightRequest(Request $request): bool
    {
        return ($request->method() === 'OPTIONS' && $request->hasHeader('Access-Control-Request-Method'));
    }


    /**
     * Get the middleware for the router.
     * 
     * @return array<string>
     */
    private function middleware(): array
    {
        return $this->app->getConfig('middleware');
    }
}

<?php

namespace Frame\Routing;

use Closure;
use Frame\Application;

class Pipeline
{
    /**
     * The Application instance.
     *
     * @var \Frame\Application
     */
    private Application $app;

    /**
     * The object to be sent through the pipeline.
     *
     * @var mixed
     */
    protected mixed $passable;

    /**
     * The array of pipes class names.
     *
     * @var string[]
     */
    protected array $pipes = [];

    /**
     * The method to call on each pipe.
     *
     * @var string
     */
    protected string $method;

    /**
     * Create a new pipeline instance.
     *
     * @param  \Frame\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set the object being sent through the pipeline.
     *
     * @param  mixed  $passable
     * @return static
     */
    public function send(mixed $passable): static
    {
        $this->passable = $passable;
        return $this;
    }

    /**
     * Set the array of pipes.
     *
     * @param   string[] $pipes
     * @return  static
     */
    public function through(array $pipes): static
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * Set the method to call on each pipe.
     *
     * @param  string  $method
     * @return static
     */
    public function via(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Run the pipeline with a final destination callback.
     *
     * @param  \Closure  $destination
     * @return mixed
     */
    public function then(Closure $destination): mixed
    {
        $pipeline = array_reduce(
            $this->reversedPipes(),
            $this->reducer(),
            $this->prepareDestination($destination)
        );

        return $pipeline();
    }

    /**
     * Get a reversed array of pipes.
     *
     * @return string[]
     */
    private function reversedPipes(): array
    {
        return array_reverse($this->pipes);
    }

    /**
     * Get the reducer function.
     *
     * @return \Closure
     */
    protected function reducer(): Closure
    {
        return function ($stack, $pipe) {
            return function () use ($stack, $pipe) {
                $pipe = $this->app->resolve($pipe);

                return $pipe->{$this->method}(...[$this->passable, $stack]);
            };
        };
    }

    /**
     * Get a Closure rapping of the destination.
     *
     * @param  \Closure  $destination
     * @return \Closure
     */
    protected function prepareDestination(Closure $destination): Closure
    {
        return function () use ($destination) {
            return $destination($this->passable);
        };
    }
}

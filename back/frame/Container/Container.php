<?php

namespace Frame\Container;

use Exception;
use ReflectionClass;
use ReflectionException;
use Frame\Exceptions\ContainerException;

class Container
{
    /**
     * Names of classes registered as singletons.
     * 
     * @var string[]
     */
    protected array $sharedClasses = [];

    /**
     * Shared instances across the application (singletons).
     * 
     * @var array<string, object>
     */
    protected array $sharedInstances = [];

    /**
     * Register a class as a singleton.
     * 
     * @param string $className Class name to be registered.
     * @return $this
     */
    public function singleton(string $className): static
    {
        $this->sharedClasses[] =  $className;
        return $this;
    }

    /**
     * Resolve a class from the container.
     * 
     * @param string $class Class name to be resolved.
     * @return object Instance of the resolved class.
     *
     * @throws Exception If the class doesn't exist, or if it's not instantiable, or it has unresolvable dependencies.
     */
    public function resolve(string $class): object
    {
        // [1] If an instance of the class is currently stored as a singleton:
        // We'll just return it instead of instantiating a new one.
        if (isset($this->sharedInstances[$class])) {
            return $this->sharedInstances[$class];
        }

        // [2] Else, we will create a new instance of the class:
        // [2.1] Build an instance.
        $instance = $this->build($class);

        // [2.2] Store the instance, if the class is registered as a singleton: 
        if (in_array($class, $this->sharedClasses)) {
            $this->sharedInstances[$class] = $instance;
        }

        return $instance;
    }

    /**
     * Build a new instance of a class.
     *
     * @param string $class Class name to be instantiated.
     * @return object Instance of the resolved class.
     *
     * @throws Exception If the class doesn't exist, or if it's not instantiable, or it has unresolvable dependencies.
     */
    private function build(string $class): object
    {
        // Get the reflector.
        $reflector = $this->reflector($class);

        // Get its constructor. And if it doesn't have one, instantiate it directly.
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        // Get its dependencies. And if it doesn't have any, instantiate it directly.
        $dependencies = $constructor->getParameters();

        if (count($dependencies) === 0) {
            return new $class;
        }

        // Resolve its dependencies.
        $depInstances = $this->resolveDependencies($dependencies);

        // Finally, instatiate the class with all of its dependencies.
        return $reflector->newInstanceArgs($depInstances);
    }

    /**
     * Resolve an array of class dependencies (ReflectionParameters).
     *
     * @param ReflectionParameter[] $dependencies Array of ReflectionParameters to be resolved.
     * @return array Array of resolved dependencies.
     *
     * @throws ContainerException If any dependency isn't resolvable. 
     */
    private function resolveDependencies(array $dependencies): array
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            // Validate the dependency's type hint, to make sure it's a class name. 
            $depType = $dependency->getType();

            if (!$depType instanceof \ReflectionNamedType || $depType->isBuiltin()) {
                if ($dependency->isDefaultValueAvailable()) {
                    $result[] = $dependency->getDefaultValue();
                    continue;
                }

                throw new ContainerException(
                    "Unresolvable dependency ($dependency) in class {$dependency->getDeclaringClass()->getName()}"
                );
            }

            // Resolve each dependency.
            $depClassName = $depType->getName();

            $result = $this->resolve($depClassName);

            $results[] = $result;
        }

        return $results;
    }

    /**
     * Get & validate the reflector of a class.
     * 
     * @param string $class.
     * @return \ReflectionClass
     * 
     * @throws \Frame\Exceptions\ContainerException 
     */
    private function reflector(string $class): ReflectionClass
    {
        try {
            $reflector = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new ContainerException("Class [$class] does not exist (while building).", 0, $e);
        }

        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class ($class) is not instantiable (while building).");
        }

        return $reflector;
    }
}

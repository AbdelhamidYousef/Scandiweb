<?php

namespace Frame;

use Exception;
use Frame\Http\Request;
use Frame\Routing\Router;
use Frame\Container\Container;
use Frame\Database\Setup;

class Application extends Container
{
    /**
     * Application instance.
     * 
     * @var \Frame\Application
     */
    private static $instance;

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    private bool $hasBeenBootstrapped = false;

    /**
     * The root path of the application.
     * 
     * @var string
     */
    private string $rootPath;

    /**
     * The path of the environment file.
     * 
     * @var string
     */
    private string $environmentPath;

    /**
     * The environment file name.
     * 
     * @var string
     */
    private string $environmentFileName;

    /**
     * Create a new framework application instance.
     * 
     * @param string $rootPath The root path of the application.
     * @return void
     */
    public function __construct(string $rootPath)
    {
        $this->setRootPath($rootPath);
        $this->setInstance();
    }

    /**
     * Boot the application's Bootstrappers.
     * 
     * @return $this
     */
    public function bootstrap(): static
    {
        if ($this->hasBeenBootstrapped()) return $this;

        $this->bootstrapped();

        foreach ($this->bootstrappers() as $bootstrapper) {
            $this->resolve($bootstrapper)->boot($this);
        }

        return $this;
    }

    /**
     * Process incoming request.
     *
     * @param  \Frame\Http\Request  $request
     * @return mixed
     */
    public function process(Request $request): mixed
    {
        return $this->resolve(Router::class)
            ->dispatch($request);
    }

    /**
     * Set up the database.
     * 
     * @param  array  $tables
     * @return void
     */
    public function setupDatabase(array $tables): void
    {
        $this->resolve(Database\Setup::class)
            ->create($tables);
    }

    /**
     * Set the shared instance of the application.
     *
     * @return $this
     */
    private function setInstance(): static
    {
        $this->sharedInstances[static::class] = $this;
        static::$instance = $this;

        return $this;
    }

    /**
     * Get the shared instance of the application.
     * 
     * @return 
     * 
     * @throws \Exception
     */
    public static function instance(): static
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        throw new Exception('Application instance not found.');
    }

    /**
     * Return if the application has been bootstrapped or not.
     *
     * @return bool
     */
    private function hasBeenBootstrapped(): bool
    {
        return $this->hasBeenBootstrapped;
    }

    /**
     * Set that the application has been bootstrapped.
     * 
     * @return $this
     */
    private function bootstrapped(): static
    {
        $this->hasBeenBootstrapped = true;
        return $this;
    }

    /**
     * Get the bootstrap classes.
     *
     * @return string[]
     */
    private function bootstrappers(): array
    {
        return $this->getConfig('bootstrappers');
    }

    /**
     * Get the service provider classes.
     *
     * @return string[]
     */
    public function serviceProviders(): array
    {
        return $this->getConfig('serviceProviders');
    }

    /**
     * Get the content of a configuration file.
     * 
     * @param  string  $fileName
     * @return mixed
     */
    public function getConfig(string $fileName): mixed
    {
        return require $this->configPath($fileName) . '.php';
    }

    /**
     * Get the content of a table SQL file.
     * 
     * @param  string  $tableName
     * @return mixed
     */
    public function getTable(string $tableName): mixed
    {
        return file_get_contents($this->tablesPath($tableName . '.sql'));
    }

    /**
     * Set the root path for the application.
     *
     * @param  string  $rootPath
     * @return $this
     */
    private function setRootPath(string $rootPath): static
    {
        $this->rootPath = rtrim($rootPath, '\/');
        return $this;
    }

    /**
     * Get the application's root path.
     *
     * @param  string  $path
     * @return string
     */
    public function rootPath(string $path = ''): string
    {
        return $this->joinPaths($this->rootPath, $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath(string $path = ''): string
    {
        return $this->joinPaths($this->rootPath('config'), $path);
    }

    /**
     * Get the path to the application tables SQL files.
     *
     * @param  string  $path
     * @return string
     */
    public function tablesPath(string $path = ''): string
    {
        return $this->joinPaths($this->rootPath('database/tables'), $path);
    }

    /**
     * Set the environment file path.
     * 
     * @param string $path Path of the environment file.
     * @return $this
     */
    public function setEnvironmentFilePath(string $path): static
    {
        $this->environmentPath = $path;
        return $this;
    }

    /**
     * Get the environment file path.
     * 
     * @return string Path of the environment file.
     */
    public function environmentPath(): string
    {
        return $this->environmentPath ?? $this->rootPath();
    }

    /**
     * Get the routes file path.
     *
     * @return string Routes file path.
     */
    public function routesPath(): string
    {
        return $this->rootPath('routes/routes.php');
    }

    /**
     * Join the given paths together.
     *
     * @param  string  $path1
     * @param  string  $path2
     * @return string
     */
    protected function joinPaths(string $path1, string $path2 = ''): string
    {
        return rtrim($path1, '\/') . ($path2 ? '/' . trim($path2, '/') : '');
    }

    /**
     * Set the environment file name.
     * 
     * @param string $name Environment file name.
     * @return $this
     */
    public function setEnvironmentName(string $name): static
    {
        $this->environmentFileName = $name;
        return $this;
    }

    /**
     * Get the environment file name.
     * 
     * @return string Name of the environment file.
     */
    public function environmentFileName(): string
    {
        return $this->environmentFileName ?? '.env';
    }
}

<?php

namespace Frame\Database;

use Frame\Application;
use PDO;
use PDOException;

class Connection
{
    /**
     * Application instance.
     * 
     * @var \Frame\Application
     */
    private Application $app;

    /**
     * PDO connection instance.
     * 
     * @var \PDO
     */
    private PDO $connection;

    /**
     * Create a new database connection instance.
     * 
     * @param \Frame\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Execute a query on the database.
     * 
     * @param string $sql
     * @param array $params
     */
    public function query($sql, $params = [])
    {
        $statement = $this->connection()->prepare($sql);

        $statement->execute($params);

        return $statement;
    }

    /**
     * Create the database if it does not exist.
     * 
     * @return $this
     */
    public function createDatabase(): static
    {
        $dbname = $this->configuration()['dbname'];

        $this->connection(true)->query("CREATE DATABASE IF NOT EXISTS {$dbname}");

        $this->connection()->exec("USE $dbname");

        return $this;
    }

    /**
     * Create tables if they do not exist.
     * 
     * @param  array $tables
     * @return $this
     */
    public function createTables(array $tables): static
    {
        foreach ($tables as $table) {
            $tableSql = $this->app->getTable($table);

            $this->connection()->exec($tableSql);
        }

        return $this;
    }

    /**
     * Connect to the database.
     * 
     * @param  bool $withoutDatabase
     * @return \PDO
     */
    public function connection($withoutDatabase = false): PDO
    {
        if (!isset($this->connection)) {
            $this->connection = $this->connect($this->configuration($withoutDatabase));
        }

        return $this->connection;
    }

    /**
     * Create a new database connection.
     * 
     * @param array $config
     * @return \PDO
     * 
     * @throws \PDOException
     */
    private function connect(array $config)
    {
        extract($config, EXTR_SKIP);

        $dsn =  $this->generateDsn($driver, $host, $dbname ?? null);

        return new PDO($dsn, $username, $password, $options ?? []);
    }

    /**
     * Get the database configuration.
     * 
     * @param bool $withoutDatabase
     * @return array
     */
    private function configuration($withoutDatabase = false): array
    {
        $config = $this->app->getConfig('database');

        if ($withoutDatabase) {
            unset($config['dbname']);
        }

        return $config;
    }

    /**
     * Generate the DSN string.
     * 
     * @param  string $driver
     * @param  string $host
     * @param  string|null $dbname
     * @return string
     */
    private function generateDsn($driver, $host, $dbname): string
    {
        $dsn = "$driver:host={$host}";

        if (isset($dbname)) {
            $dsn .= ";dbname={$dbname}";
        }

        return $dsn;
    }

    /**
     * Get the last inserted ID.
     * 
     */
    public function lastInsertId()
    {
        return $this->connection()->lastInsertId();
    }
}

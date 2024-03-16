<?php

namespace Frame\Database;

use Frame\Application;

class Setup
{
    /**
     * Application instance.
     * 
     * @var \Frame\Application
     */
    private Application $app;

    /**
     * Connection instance.
     * 
     * @var \Frame\Database\Connection
     */
    private Connection $connection;

    /**
     * Create a new instance.
     * 
     * @param  \Frame\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Create the database tables.
     * 
     * @param  array $tables
     */
    public function create(array $tables): void
    {
        $this->connection()
            ->createDatabase()
            ->createTables($tables);

        echo "Database updated successfully";
    }

    /**
     * Get the database connection instance.
     * 
     * @return \Frame\Database\Connection
     */
    private function connection(): Connection
    {
        if (!isset($this->connection)) {
            $this->connection = $this->app->resolve(Connection::class);
        }

        return $this->connection;
    }
}

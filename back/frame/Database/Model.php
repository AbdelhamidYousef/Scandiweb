<?php

namespace Frame\Database;

use App\Models\Product;
use Frame\Application;
use PDO;
use PDOStatement;

class Model
{
    /**
     * The database connection instance.
     * 
     * @var \Frame\Database\Connection
     */
    protected static Connection $connection;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected static string $table;

    /**
     * The namespace associated with the model.
     * 
     * @var string
     */
    protected static string $namespace;

    /**
     * Select all records from the database.
     * 
     */
    public static function all()
    {
        $model = new self;

        $sql = $model->selectQuery('*', static::table());

        return $model->query($sql)->fetchAll();
    }

    /**
     * Find a record by its ID.
     * 
     * @param  int  $id
     */
    public static function find(int $id)
    {
        $model = new self;

        $sql = $model->selectQuery('*', static::table(), 'id = :id');

        $params = [':id' => $id];

        return $model->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find last inserted record.
     * 
     * @return array
     */
    public static function last()
    {
        return static::find(static::connection()->lastInsertId());
    }

    /**
     * Delete a record by its ID.
     * 
     * @param  int  $id
     */
    public static function destroy(int $id)
    {
        $model = new self;

        $sql = $model->destroyQuery(static::$table, 'id = :id');

        $params = [':id' => $id];

        return $model->query($sql, $params);
    }

    /**
     * Generate the select query.
     * 
     * @param  array|string $columns
     * @param  string  $table
     * @param  string  $wheres
     * @return string
     */
    protected function selectQuery(array|string $columns, string $table, string $wheres = ''): string
    {
        $columns = $columns === '*' ? $columns : implode(', ', $columns);
        $wheres = $wheres ? " WHERE ($wheres)" : '';

        $sql = sprintf(
            "SELECT %s FROM %s%s",
            $columns,
            $table,
            $wheres
        );

        return $sql;
    }

    /**
     * Generate the insert query.
     * 
     * @param  array $columns
     * @return string
     */
    protected function insertQuery(array $columns): string
    {
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columns = implode(', ', $columns);

        return sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $this->table(),
            $columns,
            $placeholders
        );
    }

    /**
     * Generate the destroy query.
     * 
     * @return string
     */
    protected function destroyQuery(string $table, string $wheres): string
    {
        $wheres = " WHERE ($wheres)";

        return sprintf(
            "DELETE FROM `%s`%s",
            $table,
            $wheres
        );
    }

    /**
     * Send the query to the database connection.
     * 
     * @param  string $sql
     * @param  array  $params
     * @return \PDOStatement|false
     */
    protected function query(string $sql, array $params = []): PDOStatement|false
    {
        return $this->connection()->query($sql, $params);
    }

    /**
     * Get the database connection instance.
     * 
     * @return \Frame\Database\Connection
     */
    protected static function connection(): Connection
    {
        if (!isset(static::$connection)) {
            static::$connection = Application::instance()->resolve(Connection::class);
        }

        return static::$connection;
    }

    /**
     * Get The table associated with this model.
     * 
     * @return string
     */
    public static function table(): string
    {
        return static::$table;
    }

    /**
     * Get The namespace associated with this model.
     * 
     * @return string
     */
    public static function namespace(): string
    {
        return static::$namespace;
    }
}

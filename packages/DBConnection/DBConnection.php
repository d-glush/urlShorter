<?php

namespace App\DBConnection;

use mysqli;
use phpDocumentor\Reflection\UsingTagsTest;
use RuntimeException;

class DBConnection {
    private const INSERT_QUERY_FORMAT = 'INSERT INTO %1$s (%2$s) VALUES (%3$s)';
    private const SELECT_QUERY_FORMAT = 'SELECT * FROM %1$s %2$s %3$s %4$s';
    private const UPDATE_QUERY_FORMAT = 'UPDATE %1$s SET %2$s WHERE id=%3$d';

    private mysqli $connection;

    /**
     * @throws DBException;
     */
    public function __construct(DBConnectionConfigDTO $configDTO)
    {
        $this->connection = new mysqli(
            $configDTO->getHost(),
            $configDTO->getUsername(),
            $configDTO->getPassword(),
            $configDTO->getDatabaseName(),
            $configDTO->getPort()
        );

        if ($this->connection->errno) {
            throw new DBException($this->connection->errno);
        }
    }

    /**
     * @throws DBException
     */
    public function insert(string $tableName, array $data): int
    {
        $columnsExploded = [];
        $valuesExploded = [];
        foreach ($data as $column => $value) {
            $dataQueryPartExploded[] = "`$column` = '$value'";
            $columnsExploded[] = "`$column`";
            $valuesExploded[] = "'$value'";
        }

        $columns = implode(',', $columnsExploded);
        $values = implode(',', $valuesExploded);

        $query = sprintf(
            self::INSERT_QUERY_FORMAT,
            $tableName,
            $columns,
            $values
        );

        if (!$this->connection->query($query)) {
            throw new DBException();
        }
        return $this->connection->insert_id;
    }

    /**
     * @throws DBException
     */
    public function select(string $tableName, ?string $where = null, ?string $orderBy = null, ?string $limit = null): array
    {
        $whereQueryPart = '';
        if ($where) {
            $whereQueryPart = "WHERE $where";
        }

        $orderQueryPart = '';
        if ($orderBy) {
            $orderQueryPart = "ORDER BY $orderBy";
        }

        $limitQueryPart = '';
        if ($limit) {
            $limitQueryPart = "LIMIT $limit";
        }

        $query = sprintf(
            self::SELECT_QUERY_FORMAT,
            $tableName,
            $whereQueryPart,
            $orderQueryPart,
            $limitQueryPart
        );

        $queryResult = $this->connection->query($query);
        if (!$queryResult) {
            throw new DBException();
        }

        return $queryResult->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @throws DBException
     */
    public function update(string $tableName, int $id, array $data): bool
    {
        $dataQueryPartExploded = [];
        foreach ($data as $column => $value) {
            $dataQueryPartExploded[] = "`$column` = '$value'";
        }
        $dataQueryPart = implode(', ', $dataQueryPartExploded);

        $query = sprintf(
            self::UPDATE_QUERY_FORMAT,
            $tableName,
            $dataQueryPart,
            $id
        );

        $result = $this->connection->query($query);
        if (!$result) {
            throw new DBException();
        }
        return $result;
    }
}
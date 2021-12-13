<?php

namespace App\DBConnection;

use mysqli;

class DBConnection {
    private const INSERT_QUERY_FORMAT = 'INSERT INTO %1$s (%2$s) VALUES (%3$s)';
    private const SELECT_QUERY_FORMAT = 'SELECT * FROM %1$s %2$s';
    private const UPDATE_QUERY_FORMAT = 'UPDATE %1$s SET %2$s WHERE id=%3$d';

    private mysqli $connection;

    /**
     * @throws DBException;
     */
    public function __construct(MysqliWrapper $mysqli, DBConnectionConfigDTO $configDTO)
    {
        ini_set('display_errors','Off');
        //crap?
        $this->connection = $mysqli;

        $this->connection->connect(
            $configDTO->getHost(),
            $configDTO->getUsername(),
            $configDTO->getPassword(),
            $configDTO->getDatabaseName(),
            $configDTO->getPort()
        );

        if ($this->connection->isConnectError()) {
            throw new DBException($this->connection->connect_errno);
        }
        ini_set('display_errors','On');
    }

    /**
     * @throws DBException
     */
    public function insert(string $tableName, array $data): int
    {
        $columnsExploded = [];
        $valuesExploded = [];
        foreach ($data as $column => $value) {
            $columnsExploded[] = "`$column`";
            if (is_float($value) || is_int($value)) {
                $valuesExploded[] = "$value";
            } else {
                $valuesExploded[] = "'$value'";
            }
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
        return $this->connection->getInsertId();
    }

    /**
     * @throws DBException
     */
    public function select(string $tableName, ?string $where = null): array
    {
        $whereQueryPart = '';
        if ($where) {
            $whereQueryPart = "WHERE $where";
        }

        $query = sprintf(
            self::SELECT_QUERY_FORMAT,
            $tableName,
            $whereQueryPart,
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
            if (is_float($value) || is_int($value)) {
                $dataQueryPartExploded[] = "`$column`=$value";
            } else {
                $dataQueryPartExploded[] = "`$column`='$value'";
            }
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
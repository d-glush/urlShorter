<?php

namespace App\DBConnection;

class DBConnectionConfigDTO {
    private string $host;
    private string $username;
    private string $password;
    private string $databaseName;
    private int $port;

    public function __construct(array $data)
    {
        $this->host = $data['host'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->databaseName = $data['database'];
        $this->port = $data['port'];
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
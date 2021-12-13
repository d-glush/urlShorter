<?php

namespace App\DBConnection;

use mysqli;

/**
 * @codeCoverageIgnore
 */
class MysqliWrapper extends mysqli
{
    public function isConnectError(): bool
    {
        return !!$this->connect_errno;
    }

    /**
     * @return int|string
     */
    public function getInsertId(): int
    {
        return $this->insert_id;
    }
}
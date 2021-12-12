<?php

namespace App\ShortUrlRepository;

use App\DBConnection\DBConnection;
use RuntimeException;

class ShortUrlRepository {
    private const TABLE_NAME = 'shorts';
    private DBConnection $connection;

    public function __construct(DBConnection $DBConnection)
    {
        $this->connection = $DBConnection;
    }

    /**
     * @return ShortUrlObj|false
     */
    public function getShortByShortUrl(string $shortUrl)
    {
        $data = $this->connection->select(self::TABLE_NAME, "short_url = '$shortUrl'");
        if (count($data) === 0) {
            return false;
        }
        return new ShortUrlObj(new ShortUrlDTO($data[0]));
    }

    /**
     * @return ShortUrlObj|false
     */
    public function getShortByFullUrl(string $fullUrl, bool $isCustom = false)
    {
        $isCustomWhere = 'AND is_custom' . ($isCustom ? '<>0' : '=0');
        $where = "full_url = '$fullUrl' " . $isCustomWhere;
        $data = $this->connection->select(self::TABLE_NAME, $where);
        if (count($data) === 0) {
            return false;
        }
        return new ShortUrlObj(new ShortUrlDTO($data[0]));
    }

    /**
     * @throws RuntimeException
     */
    public function insertShort(ShortUrlObj $shortUrlObj): int
    {
        return $this->connection->insert(
            self::TABLE_NAME,
            [
                'full_url' => $shortUrlObj->getFullUrl(),
                'short_url' => $shortUrlObj->getShortUrl(),
                'is_custom' => +$shortUrlObj->getIsCustom(),
            ]
        );
    }

    public function updateShort(int $id, ShortUrlObj $shortUrlObj): bool
    {
        return $this->connection->update(
            self::TABLE_NAME,
            $id,
            [
                'full_url' => $shortUrlObj->getFullUrl(),
                'short_url' => $shortUrlObj->getShortUrl(),
                'is_custom' => +$shortUrlObj->getIsCustom(),
            ]
        );
    }
}
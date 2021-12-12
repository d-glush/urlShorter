<?php

namespace App\ConfigService;

Class ConfigService
{
    private ?array $config = null;

    public function __construct(string $configPath = __DIR__ . '\..\..\core\config.php')
    {
        $this->config = include $configPath;
    }

    public function getLogsFileName(): string
    {
        return $this->config['logFileName'];
    }

    public function getMaxCustomUrlLength(): int
    {
        return $this->config['maxCustomUrlLength'];
    }

    public function getGeneratingShortUrlLength(): int
    {
        return $this->config['generatingShortUrlLength'];
    }

    public function getAvailableShortUrlChars(): array
    {
        return $this->config['availableShortUrlChars'];
    }

    public function getDBConnectionConfig(): array
    {
        return  $this->config['DBConnectionConfigDTO'];
    }

    public function getFullUrlConnectTimeoutTime(): int
    {
        return  $this->config['validateFullUrlConnectTimeoutTimeMs'];
    }
}
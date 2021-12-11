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

    public function getMaxCustomUrlLength(): string
    {
        return $this->config['maxCustomUrlLength'];
    }
}
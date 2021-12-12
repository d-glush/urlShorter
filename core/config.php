<?php

return [
    'DBConnectionConfigDTO' => [
        'host' => 'localhost',
        'username' => 'mysql',
        'password' => 'mysql',
        'database' => 'urlShorter',
        'port' => 3306,
    ],

    'logFileName' => $_SERVER['DOCUMENT_ROOT'] . '/logs/log.log',

    'validateFullUrlConnectTimeoutTimeMs' => 4000,
    'maxCustomUrlLength' => 8,
    'generatingShortUrlLength' => 8,
    'availableShortUrlChars' => [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
        's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
    ],
];


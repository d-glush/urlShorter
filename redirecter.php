<?php

use App\ConfigService\ConfigService;
use App\DBConnection\DBConnection;
use App\DBConnection\DBConnectionConfigDTO;
use App\DBConnection\DBException;
use App\DBConnection\MysqliWrapper;
use App\ShortUrlRepository\ShortUrlRepository;
use App\UrlShorterService\ShortUrlGenerator;
use App\UrlShorterService\UrlShorterService;

require_once $_SERVER["DOCUMENT_ROOT"] . '/core/init.php';

$shortUrl = $_GET['q'];

$configService = new ConfigService();
$dBConnectionConfig = new DBConnectionConfigDTO($configService->getDBConnectionConfig());
try {
    $DBConnection = new DBConnection(new MysqliWrapper, $dBConnectionConfig);
} catch (DBException $e) {
    header('Location: /');
    exit;
}
$shortUrlRepository = new ShortUrlRepository($DBConnection);
$shortUrlGenerator = new ShortUrlGenerator($configService);
$urlShorterService = new UrlShorterService($shortUrlRepository, $shortUrlGenerator);

$shortUrlObj = $urlShorterService->getByShortUrl($shortUrl);
if (!$shortUrlObj) {
    header('Location: /');
} else {
    header('Location: ' . $shortUrlObj->getFullUrl());
}
http_response_code(301);
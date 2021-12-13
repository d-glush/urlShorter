<?php

use Api\CreateShortUrlApi\CreateShortUrlApi;
use App\ConfigService\ConfigService;
use App\DBConnection\DBConnection;
use App\DBConnection\DBConnectionConfigDTO;
use App\DBConnection\DBException;
use App\DBConnection\MysqliWrapper;
use App\Response\Response;
use App\Response\ResponseErrorEnum;
use App\ShortUrlRepository\ShortUrlRepository;
use App\UrlShorterService\ShortUrlGenerator;
use App\UrlShorterService\UrlShorterService;

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

require_once $_SERVER["DOCUMENT_ROOT"] . '/core/init.php';

$method = $_GET['q'];

$result = null;
switch($method) {
    case 'create_short_url':
        $configService = new ConfigService();
        $dBConnectionConfig = new DBConnectionConfigDTO($configService->getDBConnectionConfig());
        try {
            $DBConnection = new DBConnection(new MysqliWrapper, $dBConnectionConfig);
        } catch (DBException $e) {
            $result = new Response();
            $result->setError(ResponseErrorEnum::DATABASE_ERROR);
            break;
        }
        $shortUrlRepository = new ShortUrlRepository($DBConnection);
        $shortUrlGenerator = new ShortUrlGenerator($configService);
        $urlShorterService = new UrlShorterService($shortUrlRepository, $shortUrlGenerator);

        $createShortUrlRoute = new CreateShortUrlApi($urlShorterService, $configService);
        //$createShortUrlRoute = Di::createFromDi('CreateShortUrlApi');
        $result = $createShortUrlRoute->process();
        break;
    default:
        http_response_code(404);
        $result = new Response();
        $result->setError(ResponseErrorEnum::INVALID_METHOD);
}

$response = json_encode($result);
echo $response;
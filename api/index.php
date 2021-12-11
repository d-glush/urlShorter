<?php

use Api\CreateShortUrlApi\CreateShortUrlApi;
use App\ConfigService\ConfigService;
use App\Response\Response;
use App\Response\ResponseErrorEnum;
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
        $urlShorterService = new UrlShorterService();
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
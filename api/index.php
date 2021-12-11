<?php

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

require_once $_SERVER["DOCUMENT_ROOT"] . '/core/init.php';

$method = $_GET['q'];

$result = null;
switch($method) {
    case 'create_short_url':
//        $createShortUrlRoute = new CreateShortUrlApi();
//        $result = $createShortUrlRoute->process();
        break;
    default:
        http_response_code(404);
        echo json_encode('wrong method name');
        exit;
}

$response = json_encode($result);
echo $response;